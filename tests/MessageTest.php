<?php

namespace NotificationChannels\Bandwidth\Test;

use NotificationChannels\Bandwidth\BandwidthMessage;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    /**
     * @var BandwidthMessage
     */
    protected $message;


    public function setUp()
    {
        parent::setUp();
        $this->message = new BandwidthMessage();
    }

    /** @test */
    public function it_can_accept_a_message_when_constructing_a_message()
    {
        $message = new BandwidthMessage('myMessage');
        $this->assertEquals('myMessage', $message->getParameter('text'));
    }

    /** @test */
    public function it_can_set_the_content()
    {
        $this->message->content('myMessage');
        $this->assertEquals('myMessage', $this->message->getParameter('text'));
    }

    /** @test */
    public function it_can_set_the_from_number()
    {
        $this->message->from('+1234567890');
        $this->assertEquals('+1234567890', $this->message->getParameter('from'));
    }

    /** @test */
    public function it_has_null_as_default_media_value()
    {
        $this->assertNull($this->message->getParameter('media'));
    }

    /** @test */
    public function it_can_set_the_media()
    {
        $this->message->media('https://localhost/image.png');
        $this->assertEquals('https://localhost/image.png', $this->message->getParameter('media'));
    }

    /** @test */
    public function it_has_empty_array_as_default_http_value()
    {
        $this->assertEmpty($this->message->getParameter('http'));
    }

    /** @test */
    public function it_can_set_the_http_body()
    {
        $httpBody = [
            'tag' => 'info'
        ];

        $this->message->http($httpBody);
        $this->assertSame($this->message->getParameter('tag'), $httpBody['tag']);
    }
}
