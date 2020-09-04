<?php

namespace NotificationChannels\Bandwidth;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

class BandwidthMessage implements Arrayable
{
    /**
     * @var array
     */
    protected $payload = [];

    /**
     * Create a new message instance.
     *
     * @param  string  $content
     *
     * @return void
     */
    public function __construct($content = '')
    {
        $this->setParameter('text', $content);
    }

    /**
     * Create a new message instance.
     *
     * @param  string  $content
     *
     * @return BandwidthMessage
     */
    public static function create($content = '')
    {
        return new self($content);
    }

    /**
     * Set the message content.
     *
     * @param  string  $content
     *
     * @return $this
     */
    public function content(string $content)
    {
        return $this->setParameter('text', $content);
    }

    /**
     * Set the phone number the message should be sent from.
     *
     * @param  string  $from
     *
     * @return $this
     */
    public function from(string $from)
    {
        return $this->setParameter('from', $from);
    }

    /**
     * Set the media url(s) for MMS messages.
     *
     * @source https://dev.bandwidth.com/faq/messaging/mediaType.html
     * @param  string|array  $media
     *
     * @return $this
     */
    public function media($media)
    {
        return $this->setParameter('media', Arr::wrap($media));
    }

    /**
     * Set additional request options for the Guzzle HTTP client.
     * Note: this method can overwrite existing keys in payload.
     *
     * @param  array  $body
     *
     * @return $this
     */
    public function http(array $body)
    {
        $this->payload = array_merge($this->payload, $body);

        return $this;
    }

    /**
     * Set parameters.
     *
     * @param  string  $key
     * @param  mixed  $value
     *
     * @return $this
     */
    public function setParameter(string $key, $value)
    {
        Arr::set($this->payload, $key, $value);

        return $this;
    }

    /**
     * Get parameters.
     *
     * @param  string  $key
     * @param  mixed  $default
     *
     * @return mixed
     */
    public function getParameter(string $key, $default = null)
    {
        return Arr::get($this->payload, $key, $default);
    }

    /**
     * The array representation of message.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->payload;
    }
}
