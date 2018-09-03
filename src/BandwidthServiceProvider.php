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

        $this->app->bind(BandwidthChannel::class, function ($app) {
            $bandwidthConfig = $app->make(BandwidthConfig::class);

            return new BandwidthChannel(
                $app->make(BandwidthClient::class),
                $bandwidthConfig->getFrom()
            );
        });

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang/', 'bandwidth');

        $this->publishes([
            __DIR__ . '/../resources/lang/' => resource_path('lang/vendor/bandwidth'),
        ]);
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
