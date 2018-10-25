<?php

namespace NotificationChannels\Bandwidth;

use Illuminate\Notifications\Notification;
use Psr\Log\LoggerInterface;

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

    protected $logger;

    /**
     * Create a new bandwidth channel instance.
     *
     * @param BandwidthClient $client
     * @param BandwidthConfig $config
     */
    public function __construct(BandwidthClient $client, BandwidthConfig $config, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->config = $config;
        $this->logger = $logger;
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

        $payload = $this->getPayload($message, $to);

        if ($this->config->simulate()) {
            $this->logger->debug('Bandwidth Message:', $payload);
            return $payload;
        }

        return $this->client->sendMessage($payload);
    }

    /**
     * @param $message BandwidthMessage
     * @param $to string
     * @return array
     */
    public function getPayload(BandwidthMessage $message, $to)
    {
        return array_merge([
            'from' => $message->from ?: $this->config->getFrom(),
            'to' => $to,
            'text' => $message->content,
            'media' => $message->media,
        ], $message->http);
    }
}
