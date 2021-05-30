<?php

namespace NotificationChannels\Bandwidth;

use Illuminate\Support\ServiceProvider;
use NotificationChannels\Bandwidth\Exceptions\InvalidConfigException;

class BandwidthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->when(BandwidthConfig::class)
            ->needs('$config')
            ->give(function () {
                $config = $this->app['config']->get('services.bandwidth');

                if (\is_null($config)) {
                    throw InvalidConfigException::missingConfig();
                }

                return $config;
            });
    }

    public function register()
    {
        //
    }
}
