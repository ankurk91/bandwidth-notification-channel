<?php

namespace NotificationChannels\Bandwidth\Tests;

use NotificationChannels\Bandwidth\BandwidthServiceProvider;
use PHPUnit\Framework\TestCase;

class ProviderTest extends TestCase
{
    public function test_it_can_be_instantiated()
    {
        $provider = new BandwidthServiceProvider([]);

        $this->assertInstanceOf(BandwidthServiceProvider::class, $provider);
    }
}
