<?php

namespace NotificationChannels\Bandwidth\Tests;

use NotificationChannels\Bandwidth\BandwidthConfig;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{

    protected BandwidthConfig $config;

    protected array $params = [
        'application_id' => 'demo_application_id',
        'account_id' => 'demo_account_id',
        'api_username' => 'demo_user',
        'api_password' => 'demo_password',
        'from' => '+1234567890',
        'http_options' => [
            'debug' => false
        ]
    ];

    public function setUp(): void
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
        $this->assertEquals($this->params['account_id'], $this->config->getAccountId());
    }

    /** @test */
    public function it_returns_the_api_token()
    {
        $this->assertEquals($this->params['api_username'], $this->config->getApiUsername());
    }

    /** @test */
    public function it_returns_the_api_secret()
    {
        $this->assertEquals($this->params['api_password'], $this->config->getApiPassword());
    }

    /** @test */
    public function it_returns_the_http_debug_default_value_false()
    {
        $this->assertFalse($this->config->httpOptions()['debug']);
    }

    /** @test */
    public function it_returns_the_http_debug_value_when_set()
    {
        $config = new BandwidthConfig([
            'http_options' => [
                'debug' => true
            ],
        ]);
        $this->assertTrue($config->httpOptions()['debug']);
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
