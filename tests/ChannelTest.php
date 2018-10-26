<?php

namespace NotificationChannels\Bandwidth\Test;

use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery;
use PHPUnit\Framework\TestCase;
use NotificationChannels\Bandwidth\BandwidthChannel;
use NotificationChannels\Bandwidth\BandwidthClient;
use NotificationChannels\Bandwidth\BandwidthConfig;
use NotificationChannels\Bandwidth\BandwidthMessage;
use Psr\Log\LoggerInterface;

class ChannelTest extends TestCase
{
    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    /**
     * @var BandwidthClient
     */
    protected $client;

    /**
     * @var BandwidthChannel
     */
    protected $channel;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var BandwidthConfig
     */
    protected $config;

    public function setUp()
    {
        parent::setUp();

        $this->config = $this->getConfig();

        $this->client = Mockery::mock(BandwidthClient::class, [$this->config]);
        $this->logger = Mockery::mock(LoggerInterface::class);
        $this->channel = new BandwidthChannel($this->client, $this->config, $this->logger);
    }

    protected function getConfig($config = [])
    {
        return new BandwidthConfig(array_merge([
            'user_id' => 'user_id',
            'api_token' => 'token',
            'api_secret' => 'secret',
            'from' => '+1234567890',
        ], $config));
    }

    /** @test */
    public function it_can_send_a_notification()
    {
        $this->client->shouldReceive('sendMessage')
            ->once()
            ->with([
                'from' => '+1234567890',
                'to' => '+1234567890',
                'text' => 'Test message content.',
            ]);

        $this->channel->send(new TestNotifiableModel(), new TestNotification());
    }

    /** @test */
    public function it_can_send_a_notification_without_message_instance()
    {
        $this->client->shouldReceive('sendMessage')
            ->once()
            ->with([
                'from' => '+1234567890',
                'to' => '+1234567890',
                'text' => 'Test message content.',
            ]);

        $this->channel->send(new TestNotifiableModel(), new TestNotificationWithoutMessageInstance());
    }


    /** @test */
    public function it_can_send_a_notification_from_custom_number()
    {
        $this->client->shouldReceive('sendMessage')
            ->once()
            ->with([
                'from' => '+1987654320',
                'to' => '+1234567890',
                'text' => 'Test message content.',
            ]);

        $this->channel->send(new TestNotifiableModel(), new TestNotificationWithCustomFrom());
    }

    /** @test */
    public function it_can_send_a_notification_with_media()
    {
        $this->client->shouldReceive('sendMessage')
            ->once()
            ->with([
                'from' => '+1234567890',
                'to' => '+1234567890',
                'text' => 'Test message content.',
                'media' => 'http://localhost/image.png',
            ]);

        $this->channel->send(new TestNotifiableModel(), new TestNotificationWithMedia());
    }

    /** @test */
    public function it_can_send_a_notification_with_http_body()
    {
        $this->client->shouldReceive('sendMessage')
            ->once()
            ->with([
                'from' => '+1234567890',
                'to' => '+1234567890',
                'text' => 'Test message content.',
                'tag' => 'info'
            ]);

        $this->channel->send(new TestNotifiableModel(), new TestNotificationWithHttp());
    }

    /** @test */
    public function it_can_not_send_notification_when_notifiable_does_not_have_a_phone()
    {
        $this->client->shouldNotReceive('sendMessage');

        $this->channel->send(new TestNotifiableModelWithoutPhone(), new TestNotification());
    }

    /** @test */
    public function it_can_not_send_notification_in_simulation()
    {
        $this->client->shouldNotReceive('sendMessage');
        $this->logger->shouldReceive('debug')
            ->once()
            ->with('Bandwidth Message:', [
                'from' => '+1234567890',
                'to' => '+1234567890',
                'text' => 'Test message content.',
            ]);

        $channel = new BandwidthChannel($this->client, $this->getConfig(['simulate' => true]), $this->logger);
        $channel->send(new TestNotifiableModel(), new TestNotification());
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

class TestNotificationWithHttp extends Notification
{
    public function toBandwidth($notifiable)
    {
        return (new BandwidthMessage())
            ->content("Test message content.")
            ->http([
                'tag' => 'info'
            ]);
    }
}
