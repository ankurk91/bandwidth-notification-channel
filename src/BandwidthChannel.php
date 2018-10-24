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
     * @var BandwidthConfig
     */
    protected $config;

    /**
     * Create a new bandwidth channel instance.
     *
     * @param BandwidthClient $client
     * @param BandwidthConfig $config
     */
    public function __construct(BandwidthClient $client, BandwidthConfig $config)
    {
        $this->client = $client;
        $this->config = $config;
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
        if (! $to = $notifiable->routeNotificationFor('bandwidth', $notification)) {
            return;
        }

        $message = $notification->toBandwidth($notifiable);

        if (\is_string($message)) {
            $message = new BandwidthMessage($message);
        }

        return $this->client->sendMessage($this->getPayload($message, $to));
    }

    /**
     * @param $message BandwidthMessage
     * @param $to string
     * @return array
     */
    protected function getPayload(BandwidthMessage $message, $to)
    {
        return array_merge([
            'from' => $message->from ?: $this->config->getFrom(),
            'to' => $to,
            'text' => $message->content,
            'media' => $message->media,
        ], $message->http);
    }
}
