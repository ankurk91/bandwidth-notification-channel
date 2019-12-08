<?php

namespace NotificationChannels\Bandwidth\Test;

use NotificationChannels\Bandwidth\BandwidthConfig;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    /**
     * @var BandwidthConfig
     */
    protected $config;

    /**
     * @var array
     */
    protected $params = [
        'application_id' => 'demo_application_id',
        'user_id' => 'demo_user_id',
        'api_token' => 'demo_token',
        'api_secret' => 'demo_secret',
        'from' => '+1234567890',
    ];

    public function setUp() : void
    {
        parent::setUp();
        $this->config = new BandwidthConfig($this->params);
    }

    /** @test */
    public function it_returns_the_application_id()
    {
        $this->assertEquals($this->params['application_id'], $this->config->getApplicationId());
    }

    /** @test */
    public function it_returns_the_user_id()
    {
        $this->assertEquals($this->params['user_id'], $this->config->getUserId());
    }

    /** @test */
    public function it_returns_the_api_token()
    {
        $this->assertEquals($this->params['api_token'], $this->config->getApiToken());
    }

    /** @test */
    public function it_returns_the_api_secret()
    {
        $this->assertEquals($this->params['api_secret'], $this->config->getApiSecret());
    }

    /** @test */
    public function it_returns_the_http_debug_default_value_false()
    {
        $this->assertFalse($this->config->debugHttp());
    }

    /** @test */
    public function it_returns_the_http_debug_value_when_set()
    {
        $config = new BandwidthConfig([
            'debug_http' => true,
        ]);
        $this->assertTrue($config->debugHttp());
    }

    /** @test */
    public function it_returns_the_simulate_default_value_false()
    {
        $this->assertFalse($this->config->dryRun());
    }

    /** @test */
    public function it_returns_the_dry_run_value_when_set()
    {
        $config = new BandwidthConfig([
            'dry_run' => true,
        ]);
        $this->assertTrue($config->dryRun());
    }

    /** @test */
    public function it_returns_the_from_value_when_set()
    {
        $this->assertEquals($this->params['from'], $this->config->getFrom());
    }

    /** @test */
    public function it_returns_the_from_value_as_null_when_not_set()
    {
        $config = new BandwidthConfig([]);
        $this->assertNull($config->getFrom());
    }

}
