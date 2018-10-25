<?php

namespace NotificationChannels\Bandwidth;

use Illuminate\Support\ServiceProvider;
use NotificationChannels\Bandwidth\Exceptions\InvalidConfiguration;

class BandwidthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->when(BandwidthConfig::class)
            ->needs('$config')
            ->give(function () {
                $config = $this->app['config']->get('services.bandwidth');

                if (is_null($config)) {
                    throw InvalidConfiguration::missingConfig();
                }

                return $config;
            });

        $this->app->when(BandwidthChannel::class)
            ->needs('$logger')
            ->give(function () {
                return logger();
            });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(BandwidthClient::class);
    }
}
