<?php

namespace NotificationChannels\Bandwidth;

use Psr\Log\LoggerInterface;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use NotificationChannels\Bandwidth\Exceptions\CouldNotSendException;

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

    /***
     * @var Dispatcher
     */
    protected $events;

    /**
     * Create a new bandwidth channel instance.
     *
     * @param BandwidthClient $client
     * @param BandwidthConfig $config
     * @param LoggerInterface $logger
     * @param Dispatcher $dispatcher
     */
    public function __construct(
        BandwidthClient $client,
        BandwidthConfig $config,
        LoggerInterface $logger,
        Dispatcher $dispatcher)
    {
        $this->client = $client;
        $this->config = $config;
        $this->logger = $logger;
        $this->events = $dispatcher;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed $notifiable
     * @param  \Illuminate\Notifications\Notification $notification
     * @throws \Exception
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
            $this->logger->debug("Bandwidth Message-ID: <{$notification->id}>\n", $payload);

            return $payload;
        }

        return $this->sendMessage($notifiable, $notification, $payload);
    }

    /**
     * Call the API.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     * @param array $payload
     * @return mixed
     * @throws CouldNotSendException
     */
    protected function sendMessage($notifiable, Notification $notification, array $payload)
    {
        try {
            $response = $this->client->sendMessage($payload);

            return \json_decode($response->getBody(), true);
        } catch (\Throwable $exception) {
            $this->events->dispatch(new NotificationFailed(
                $notifiable,
                $notification,
                self::class,
                [
                    'message' => $exception->getMessage(),
                    'exception' => $exception,
                ]
            ));

            throw CouldNotSendException::clientError($exception);
        }
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
            'applicationId' => $this->config->getApplicationId(),
        ], $message->toArray());
    }
}
