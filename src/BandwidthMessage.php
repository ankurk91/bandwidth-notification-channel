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
}
