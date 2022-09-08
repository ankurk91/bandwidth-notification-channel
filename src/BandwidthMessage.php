<?php
declare(strict_types=1);

namespace NotificationChannels\Bandwidth;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

class BandwidthMessage implements Arrayable
{
    protected array $payload = [];

    public function __construct($content = '')
    {
        $this->setParameter('text', $content);
    }

    public static function create($content = ''): self
    {
        return new self($content);
    }

    public function content(string $content): self
    {
        return $this->setParameter('text', $content);
    }

    public function from(string $from): self
    {
        return $this->setParameter('from', $from);
    }

    /**
     * Set the media url(s) for MMS messages.
     *
     * @source https://dev.bandwidth.com/docs/messaging/media/
     */
    public function media(string|array $media): self
    {
        return $this->setParameter('media', Arr::wrap($media));
    }

    /**
     * Set additional request options for the Guzzle HTTP client.
     * Note: this method can overwrite existing keys in payload.
     */
    public function httpBody(array $body): self
    {
        $this->payload = array_merge($this->payload, $body);

        return $this;
    }

    public function setParameter(string $key, $value): self
    {
        Arr::set($this->payload, $key, $value);

        return $this;
    }

    public function getParameter(string $key, $default = null)
    {
        return Arr::get($this->payload, $key, $default);
    }

    public function toArray(): array
    {
        return $this->payload;
    }
}
