<?php

namespace NotificationChannels\Bandwidth;

use Illuminate\Support\Arr;

class BandwidthConfig
{
    /**
     * @var array
     */
    private $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
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
    public function debugHttp()
    {
        return Arr::get($this->config, 'debug_http', false);
    }

    /**
     * Simulate API calls by logging them.
     *
     * @return bool
     */
    public function simulate()
    {
        return Arr::get($this->config, 'simulate', false);
    }
}
