<?php

namespace NotificationChannels\Bandwidth;

use GuzzleHttp\Client as GuzzleClient;

class BandwidthClient
{
    /**
     * @source https://dev.bandwidth.com/ap-docs/
     * @var string
     */
    protected $apiBaseUrl = 'https://api.catapult.inetwork.com/v1/';

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client = null;

    /**
     * Default headers
     * @var array
     */
    protected $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ];

    /*
     * @var boolean
     */
    protected $breakOnErrors = true;

    /**
     * @var string
     */
    protected $userId;

    /**
     * @var string
     */
    protected $apiToken;

    /**
     * @var string
     */
    protected $apiSecret;

    /**
     * @param $userId string
     * @param $apiToken string
     * @param $apiSecret string
     */
    public function __construct($userId, $apiToken, $apiSecret)
    {
        $this->userId = $userId;
        $this->apiToken = $apiToken;
        $this->apiSecret = $apiSecret;
    }

    /**
     * Create fresh client
     * Note: Client is immutable so we need to replace existing client
     *
     * @source http://docs.guzzlephp.org/en/stable
     */
    protected function createClient()
    {
        $this->client = new GuzzleClient([
            'base_uri' => $this->apiBaseUrl, // follows RFC 3986
            'headers' => $this->headers,
            'connect_timeout' => 10.0, // seconds,
            'timeout' => 10.0, // seconds
            'verify' => true, // SSL verification
            'http_errors' => $this->breakOnErrors, // throw exception on non 20x response codes
            'debug' => false,
            'auth' => [
                $this->apiToken, $this->apiSecret
            ]
        ]);
    }

    /**
     * Guzzle will not throw exceptions on https errors
     *
     * @return $this
     */
    public function withoutHttpErrors()
    {
        $this->breakOnErrors = false;
        return $this;
    }

    /**
     * Create and store client if does not exist yet
     */
    protected function getClient()
    {
        if (is_null($this->client)) {
            $this->createClient();
        }

        return $this->client;
    }

    /**
     * Proxy method to guzzle request() method
     *
     * @param $method
     * @param $url
     * @param array $payload
     * @return mixed|Response
     */
    protected function sendRequest($method, $url, array $payload)
    {
        return $this->getClient()->request($method, $url, array_merge_recursive(
                ['query' => $this->parseQueryParams($url)],
                $this->buildOptionsForRequest($method, $payload)
            )
        );
    }

    /**
     * Prepare payload according to http verb
     *
     * @param $method
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
     * Extract query params from url
     *
     * @param $url
     * @return array
     */
    protected function parseQueryParams($url)
    {
        $query = [];
        parse_str(parse_url($url, PHP_URL_QUERY), $query);
        return $query;
    }

    /**
     * Send SMS or MMS
     *
     * @source https://dev.bandwidth.com/ap-docs/methods/messages/postMessages.html
     * @param array $body
     * @return mixed|Response
     */
    public function sendMessage(array $body = [])
    {
        return $this->sendRequest('POST', "users/{$this->userId}/messages", $body);
    }
}
