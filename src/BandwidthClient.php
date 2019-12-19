<?php

namespace NotificationChannels\Bandwidth;

use GuzzleHttp\Client as GuzzleClient;

class BandwidthClient
{
    /**
     * @source https://dev.bandwidth.com/messaging/about.html
     * @var string
     */
    const API_BASE_URL = 'https://messaging.bandwidth.com/api/v2/';

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
     * @param  BandwidthConfig  $config
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
     * @param  array  $options
     *
     * @return $this
     */
    public function withOptions(array $options)
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
     * @param  string  $method
     * @param  string  $url
     * @param  array  $payload
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
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
     * @param  string  $method
     * @param  array  $payload
     *
     * @return array
     */
    protected function buildOptionsForRequest($method, array $payload): array
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
     * @param  string  $url
     *
     * @return array
     */
    protected function parseQueryParams(string $url): array
    {
        $query = [];
        parse_str(parse_url($url, PHP_URL_QUERY), $query);

        return $query;
    }

    /**
     * Send SMS or MMS.
     *
     * @source https://dev.bandwidth.com/messaging/methods/messages/createMessage.html
     * @param  array  $body
     *
     * @return mixed
     */
    public function sendMessage(array $body = [])
    {
        return $this->sendRequest('POST', 'messages', $body);
    }
}
