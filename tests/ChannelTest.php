<?php

namespace NotificationChannels\Bandwidth\Test;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Client\Factory as HttpClient;
use Illuminate\Http\Client\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery;
use NotificationChannels\Bandwidth\BandwidthChannel;
use NotificationChannels\Bandwidth\BandwidthConfig;
use NotificationChannels\Bandwidth\BandwidthMessage;
use NotificationChannels\Bandwidth\Exceptions\BandwidthBaseException;
use NotificationChannels\Bandwidth\Exceptions\BandwidthRequestException;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class ChannelTest extends TestCase
{
    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    protected HttpClient $client;

    protected BandwidthChannel $channel;

    protected LoggerInterface $logger;

    protected Dispatcher $events;

    protected BandwidthConfig $config;

    public function setUp(): void
    {
        parent::setUp();

        $this->config = $this->getConfig();
        $this->client = new HttpClient();
        $this->logger = Mockery::mock(LoggerInterface::class);
        $this->events = Mockery::mock(Dispatcher::class);
        $this->channel = new BandwidthChannel($this->client, $this->config, $this->logger, $this->events);
    }

    protected function getConfig($config = []): BandwidthConfig
    {
        return new BandwidthConfig(array_merge([
            'application_id' => 'demo_application_id',
            'account_id' => 'demo_account_id',
            'api_username' => 'demo_user',
            'api_password' => 'demo_password',
            'from' => '+1234567890',
        ], $config));
    }

    protected function mergeWith(array $payload): array
    {
        return array_merge([
            'applicationId' => $this->config->getApplicationId(),
        ], $payload);
    }

    /** @test */
    public function it_can_send_a_notification()
    {
        $this->client->fake([
            '*' => $this->client
                ->response(['id' => 123], 200),
        ]);

        $response = $this->channel->send(new TestNotifiableModel(), new TestNotification());
        $this->assertSame(123, $response['id']);

        $this->client->assertSent(function (Request $request) {
            return $request->isJson() &&
                $request->hasHeader('Content-Type', 'application/json') &&
                $request->offsetExists('applicationId');
        });
        $this->client->assertSentCount(1);
    }

    /** @test */
    public function it_can_send_a_notification_without_message_instance()
    {
        $this->client->fake();

        $this->channel->send(new TestNotifiableModel(), new TestNotificationWithoutMessageInstance());

        $this->client->assertSent(function (Request $request) {
            return $request->offsetExists('text');
        });
    }

    /** @test */
    public function it_can_send_a_notification_from_custom_number()
    {
        $this->client->fake();

        $this->channel->send(new TestNotifiableModel(), new TestNotificationWithCustomFrom());

        $this->client->assertSent(function (Request $request) {
            return $request->offsetGet('from') === '+1987654320';
        });
    }

    /** @test */
    public function it_can_send_a_notification_with_media()
    {
        $this->client->fake();

        $this->channel->send(new TestNotifiableModel(), new TestNotificationWithMedia());
        $this->client->assertSent(function (Request $request) {
            return $request->offsetExists('media');
        });
    }

    /** @test */
    public function it_can_send_a_notification_with_http_body()
    {
        $this->client->fake();
        $this->channel->send(new TestNotifiableModel(), new TestNotificationWithHttpBody());

        $this->client->assertSent(function (Request $request) {
            return $request->offsetExists('tag');
        });
    }


    /** @test */
    public function it_should_throw_exception_on_failure()
    {
        $this->events->shouldReceive("dispatch")->once();

        $this->client->fake([
            '*' => $this->client
                ->response('Error', 500),
        ]);

        $this->expectException(BandwidthRequestException::class);

        $this->channel->send(new TestNotifiableModel(), new TestNotificationWithHttpBody());
    }

    /** @test */
    public function it_can_not_send_notification_when_notifiable_does_not_have_a_phone()
    {
        $this->client->fake();

        $this->channel->send(new TestNotifiableModelWithoutPhone(), new TestNotification());

        $this->client->assertNothingSent();
    }

    /** @test */
    public function it_can_not_send_notification_in_simulation()
    {
        $this->client->fake();

        $this->logger->shouldReceive('debug')
            ->once()
            ->with("Bandwidth Message-ID: <random-id>\n", $this->mergeWith([
                'from' => '+1234567890',
                'to' => '+1234567890',
                'text' => 'Test message content.',
            ]));

        $channel = new BandwidthChannel(
            $this->client,
            $this->getConfig(['dry_run' => true]),
            $this->logger,
            $this->events
        );

        $channel->send(new TestNotifiableModel(), new TestNotification());
        $this->client->assertNothingSent();
    }
}


class TestNotifiableModel
{
    use Notifiable;

    public function routeNotificationForBandwidth($notification)
    {
        return '+1234567890';
    }
}

class TestNotifiableModelWithoutPhone
{
    use Notifiable;

    public function routeNotificationForBandwidth($notification)
    {
        return false;
    }

}

class TestNotification extends Notification
{
    public $id = 'random-id';

    public function toBandwidth($notifiable)
    {
        return (new BandwidthMessage())
            ->content("Test message content.");
    }
}

class TestNotificationWithoutMessageInstance extends Notification
{
    public function toBandwidth($notifiable)
    {
        return 'Test message content.';
    }
}

class TestNotificationWithCustomFrom extends Notification
{
    public function toBandwidth($notifiable)
    {
        return (new BandwidthMessage())
            ->content("Test message content.")
            ->from('+1987654320');
    }
}

class TestNotificationWithMedia extends Notification
{
    public function toBandwidth($notifiable)
    {
        return (new BandwidthMessage())
            ->content("Test message content.")
            ->media('http://localhost/image.png');
    }
}

class TestNotificationWithHttpBody extends Notification
{
    public function toBandwidth($notifiable)
    {
        return BandwidthMessage::create()
            ->content("Test message content.")
            ->http([
                'tag' => 'info',
            ]);
    }
}
