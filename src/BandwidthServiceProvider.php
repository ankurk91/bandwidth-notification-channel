<?php

namespace NotificationChannels\Bandwidth;

use Illuminate\Support\ServiceProvider;
use NotificationChannels\Bandwidth\Exceptions\InvalidConfiguration;

class BandwidthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->bind(BandwidthChannel::class, function () {
            $config = config('services.bandwidth');

            if (is_null($config)) {
                throw InvalidConfiguration::configurationNotSet();
            }

            $bandwidthConfig = new BandwidthConfig($config);
            return new BandwidthChannel(
                new BandwidthClient(
                    $bandwidthConfig
                ),
                $bandwidthConfig->getFrom()
            );
        });
    }
}
