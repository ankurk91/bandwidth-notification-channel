<?php

namespace NotificationChannels\Bandwidth;

use Psr\Log\LoggerInterface;
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
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Create a new bandwidth channel instance.
     *
     * @param BandwidthClient $client
     * @param BandwidthConfig $config
     * @param LoggerInterface $logger
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

        $payload = $this->payload($message, $to);

        if ($this->config->simulate()) {
            $this->logger->debug('Bandwidth Message:', $payload);

            return $payload;
        }

        return $this->client->sendMessage($payload);
    }

    /**
     * Prepare the http payload.
     *
     * @param BandwidthMessage $message
     * @param string $to
     * @return array
     */
    protected function payload(BandwidthMessage $message, $to)
    {
        return array_merge([
            'from' => $this->config->getFrom(),
            'to' => $to,
        ], $message->toArray());
    }
}
