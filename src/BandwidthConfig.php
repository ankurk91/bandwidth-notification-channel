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
     * Get the user id.
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->config['user_id'];
    }

    /**
     * Get the token.
     *
     * @return string
     */
    public function getApiToken()
    {
        return $this->config['api_token'];
    }

    /**
     * Get the secret.
     *
     * @return string
     */
    public function getApiSecret()
    {
        return $this->config['api_secret'];
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
