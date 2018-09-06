<?php

namespace NotificationChannels\Bandwidth;

class BandwidthMessage
{
    /**
     * The message content.
     *
     * @var string
     */
    public $content;

    /**
     * The phone number the message should be sent from.
     *
     * @var string
     */
    public $from;

    /**
     * A media url to the location of the media or list of medias to be sent send with the message.
     *
     * @var string|array
     */
    public $media = null;

    /**
     * Additional request options for the Guzzle HTTP client.
     *
     * @var array
     */
    public $http = [];

    /**
     * Create a new message instance.
     *
     * @param  string $content
     * @return void
     */
    public function __construct($content = '')
    {
        $this->content = $content;
    }

    /**
     * Set the message content.
     *
     * @param  string $content
     * @return $this
     */
    public function content($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Set the phone number the message should be sent from.
     *
     * @param  string $from
     * @return $this
     */
    public function from($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Set the media url(s) for MMS messages.
     *
     * @source https://dev.bandwidth.com/faq/messaging/mediaType.html
     * @param  string|array $media
     * @return $this
     */
    public function media($media)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Set additional request options for the Guzzle HTTP client.
     *
     * @param  array  $body
     * @return $this
     */
    public function http(array $body)
    {
        $this->http = $body;

        return $this;
    }
}
