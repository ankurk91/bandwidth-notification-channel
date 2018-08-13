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

            return new BandwidthChannel(
                new BandwidthClient(
                    array_get($config, 'user_id'),
                    array_get($config, 'api_token'),
                    array_get($config, 'api_secret')
                ),
                array_get($config, 'from')
            );
        });
    }
}
