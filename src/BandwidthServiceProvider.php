<?php
declare(strict_types=1);

namespace NotificationChannels\Bandwidth;

use Illuminate\Support\ServiceProvider;

class BandwidthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/bandwidth.php' => config_path('bandwidth.php'),
            ], 'config');
        }

        $this->app->when(BandwidthConfig::class)
            ->needs('$config')
            ->give(function () {
                return $this->app['config']->get('bandwidth');
            });
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/bandwidth.php', 'bandwidth');
    }
}
