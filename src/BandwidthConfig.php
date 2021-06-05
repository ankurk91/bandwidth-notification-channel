<?php

namespace NotificationChannels\Bandwidth;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

class BandwidthConfig implements Arrayable
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getApplicationId()
    {
        return $this->config['application_id'];
    }

    public function getAccountId()
    {
        return $this->config['account_id'];
    }

    public function getApiUsername()
    {
        return $this->config['api_username'];
    }

    public function getApiPassword()
    {
        return $this->config['api_password'];
    }

    public function getFrom()
    {
        return Arr::get($this->config, 'from', null);
    }

    public function httpOptions(): array
    {
        return Arr::get($this->config, 'http_options', []);
    }

    public function dryRun(): bool
    {
        return Arr::get($this->config, 'dry_run', false);
    }

    public function toArray(): array
    {
        return $this->config;
    }
}
