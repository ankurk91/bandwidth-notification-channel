<?php

namespace NotificationChannels\Bandwidth;

class BandwidthMessage
{
    /**
     * The message content.
     *
     * @var string
     */
    public $text;

    /**
     * The phone number the message should be sent from.
     *
     * @var string
     */
    public $from;

    /**
     * A media url to the location of the media or list of medias to be sent send with the message.
     * @var string
     */
    public $media = null;

    /**
     * Create a new message instance.
     *
     * @param  string $text
     * @return void
     */
    public function __construct($text = '')
    {
        $this->text = $text;
    }

    /**
     * Set the message content.
     *
     * @param  string $text
     * @return $this
     */
    public function text($text)
    {
        $this->text = $text;

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
}
