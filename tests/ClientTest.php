<?php

namespace NotificationChannels\Bandwidth\Test;

use GuzzleHttp\Handler\MockHandler;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use NotificationChannels\Bandwidth\BandwidthClient;
use NotificationChannels\Bandwidth\BandwidthConfig;

class ClientTest extends TestCase
{
    /**
     * @var BandwidthClient
     */
    protected $client;

    /**
     * @var array
     */
    protected $history = [];

    public function setUp()
    {
        parent::setUp();

        $config = new BandwidthConfig([
            'user_id' => 'fake_user_id',
            'api_token' => 'fake_token',
            'api_secret' => 'fake_secret',
            'from' => '+1234567890',
        ]);

        $stack = HandlerStack::create(
            new MockHandler([
                new Response(200),
            ])
        );
        $stack->push(\GuzzleHttp\Middleware::history($this->history));

        $this->client = (new BandwidthClient($config))->withOptions([
            'handler' => $stack
        ]);
    }

    /** @test */
    public function it_can_return_guzzle_instance()
    {
        $this->assertInstanceOf(\GuzzleHttp\Client::class, $this->client->getClient());
    }

    /** @test */
    public function it_can_send_message_over_network()
    {
        $httpBody = [
            'to' => '+123456790',
            'from' => '+987654321',
            'text' => 'message',
        ];

        $this->client->sendMessage($httpBody);

        $this->assertCount(1, $this->history, 'History container should have one entry.');
        $this->assertEquals('POST', $this->history[0]['request']->getMethod());
        $this->assertSame(200, $this->history[0]['response']->getStatusCode());

        $this->assertContains('messages', $this->history[0]['request']->getUri()->getPath());
        $this->assertEquals(json_encode($httpBody), $this->history[0]['request']->getBody());
    }

    /** @test */
    public function it_can_send_get_request()
    {
        $this->client->sendRequest('get', 'errors?page=50');

        $this->assertCount(1, $this->history, 'History container should have one entry.');
        $this->assertEquals('GET', $this->history[0]['request']->getMethod());
        $this->assertSame(200, $this->history[0]['response']->getStatusCode());

        $this->assertContains('errors', $this->history[0]['request']->getUri()->getPath());
    }
}
