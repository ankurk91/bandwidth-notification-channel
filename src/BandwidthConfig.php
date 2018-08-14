<?php

namespace NotificationChannels\Bandwidth;

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
        return array_get($this->config, 'from', null);
    }
}
