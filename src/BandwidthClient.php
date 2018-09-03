<?php

namespace NotificationChannels\Bandwidth;

use GuzzleHttp\Client as GuzzleClient;

class BandwidthClient
{
    /**
     * @source https://dev.bandwidth.com/ap-docs/
     * @var string
     */
    const API_BASE_URL = 'https://api.catapult.inetwork.com/v1/';

    /**
     * @var \GuzzleHttp\Client
     */
    protected $guzzle = null;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var BandwidthConfig
     */
    private $config;

    /**
     * @param BandwidthConfig $config
     */
    public function __construct(BandwidthConfig $config)
    {
        $this->config = $config;
    }

    /**
     * Create fresh client
     * Note: Client is immutable so we need to replace existing client.
     *
     * @source http://docs.guzzlephp.org/en/stable
     */
    protected function createClient()
    {
        $this->guzzle = new GuzzleClient(array_merge([
            'base_uri' => self::API_BASE_URL."users/{$this->config->getUserId()}/",
            'connect_timeout' => 30,
            'timeout' => 10,
            'http_errors' => true,
            'debug' => $this->config->debugHttp(),
            'auth' => [
                $this->config->getApiToken(), $this->config->getApiSecret(),
            ],
        ], $this->options));
    }

    /**
     * Set Guzzle client options.
     *
     * @param $options array
     * @return $this
     */
    public function withOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Create and store client if does not exist yet.
     *
     * @return \GuzzleHttp\Client
     */
    public function getClient()
    {
        if (is_null($this->guzzle)) {
            $this->createClient();
        }

        return $this->guzzle;
    }

    /**
     * Proxy method to guzzle request() method.
     *
     * @param $method string
     * @param $url  string
     * @param array $payload
     * @return mixed
     */
    public function sendRequest($method, $url, array $payload = [])
    {
        $url = ltrim($url, '/');

        return $this->getClient()->request($method, $url, array_merge_recursive(
                ['query' => $this->parseQueryParams($url)],
                $this->buildOptionsForRequest($method, $payload)
            )
        );
    }

    /**
     * Prepare payload according to http verb.
     *
     * @param $method string
     * @param $payload
     * @return array
     */
    protected function buildOptionsForRequest($method, $payload)
    {
        $options = [];
        switch (strtolower($method)) {
            case 'get':
                $options['query'] = $payload;
                break;
            default:
                $options['json'] = $payload;

        }

        return $options;
    }

    /**
     * Extract query params from url.
     *
     * @param $url string
     * @return array
     */
    protected function parseQueryParams($url)
    {
        $query = [];
        parse_str(parse_url($url, PHP_URL_QUERY), $query);

        return $query;
    }

    /**
     * Send SMS or MMS.
     *
     * @source https://dev.bandwidth.com/ap-docs/methods/messages/postMessages.html
     * @param array $body
     * @return mixed
     */
    public function sendMessage(array $body = [])
    {
        return $this->sendRequest('POST', 'messages', $body);
    }
}
