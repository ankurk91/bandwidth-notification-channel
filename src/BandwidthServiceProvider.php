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
                $config = config('services.bandwidth');

                if (is_null($config)) {
                    throw new InvalidConfiguration();
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
