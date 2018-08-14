<?php

namespace NotificationChannels\Bandwidth;

use Illuminate\Notifications\Notification;

class BandwidthChannel
{
    /**
     * @var BandwidthClient
     */
    protected $client;

    /**
     * The phone number notifications should be sent from.
     *
     * @var string
     */
    protected $from;

    /**
     * Max length for SMS text
     * @var integer
     */
    const MAX_LENGTH = 2048;

    /**
     * Create a new bandwidth channel instance.
     *
     * @param BandwidthClient $client
     * @param $from string
     */
    public function __construct(BandwidthClient $client, $from = null)
    {
        $this->client = $client;
        $this->from = $from;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed $notifiable
     * @param  \Illuminate\Notifications\Notification $notification
     * @return mixed
     */
    public function send($notifiable, Notification $notification)
    {
        if (!$to = $notifiable->routeNotificationFor('bandwidth', $notification)) {
            return;
        }

        $message = $notification->toBandwidth($notifiable);

        if (\is_string($message)) {
            $message = new BandwidthMessage($message);
        }

        if (\mb_strlen($message->text) > self::MAX_LENGTH) {
            //todo throw exception
        }

        return $this->client->sendMessage([
            'from' => $message->from ?: $this->from,
            'to' => $to,
            'text' => trim($message->text),
            'media' => $message->media,
        ]);
    }
}
