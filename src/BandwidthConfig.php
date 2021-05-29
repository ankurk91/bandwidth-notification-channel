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

    /**
     * Determines whether to debug Guzzle client or not.
     * @source http://docs.guzzlephp.org/en/stable/request-options.html#debug
     *
     * @return bool
     */
    public function debugHttp(): bool
    {
        return Arr::get($this->config, 'debug_http', false);
    }

    /**
     * Simulate API calls by logging them.
     *
     * @return bool
     */
    public function dryRun(): bool
    {
        return Arr::get($this->config, 'dry_run', false);
    }

    public function toArray(): array
    {
        return $this->config;
    }
}
