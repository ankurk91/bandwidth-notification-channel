<?php

namespace NotificationChannels\Bandwidth;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

class BandwidthConfig implements Arrayable
{
    /**
     * @var array
     */
    private $config;

    /**
     * @param  array  $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Get the application id.
     *
     * @return string
     */
    public function getApplicationId()
    {
        return $this->config['application_id'];
    }

    /**
     * Get the unique account id.
     *
     * @return string
     */
    public function getAccountId()
    {
        return $this->config['account_id'];
    }

    /**
     * Get the user's username.
     *
     * @return string
     */
    public function getApiUsername()
    {
        return $this->config['api_username'];
    }

    /**
     * Get the user's password.
     *
     * @return string
     */
    public function getApiPassword()
    {
        return $this->config['api_password'];
    }

    /**
     * Get the default from phone number.
     *
     * @return string
     */
    public function getFrom()
    {
        return Arr::get($this->config, 'from', null);
    }

    /**
     * Determine whether to debug Guzzle client or not.
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

    /**
     * Get whole config as array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->config;
    }
}
