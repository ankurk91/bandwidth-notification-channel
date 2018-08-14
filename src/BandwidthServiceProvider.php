<?php

namespace NotificationChannels\Bandwidth;

use Illuminate\Support\ServiceProvider;
use NotificationChannels\Bandwidth\Exceptions\InvalidConfigurationException;

class BandwidthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(BandwidthConfig::class)
            ->needs('$config')
            ->give(function () {
                $config = config('services.bandwidth');

                if (is_null($config)) {
                    throw new InvalidConfigurationException();
                }

                return $config;
            });

        $this->app->bind(BandwidthChannel::class, function ($app) {
            $bandwidthConfig = $app->make(BandwidthConfig::class);

            return new BandwidthChannel(
                $app->make(BandwidthClient::class),
                $bandwidthConfig->getFrom()
            );
        });
    }
}
