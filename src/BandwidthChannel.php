<?php

namespace NotificationChannels\Bandwidth;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Notification;
use NotificationChannels\Bandwidth\Exceptions\CouldNotSendException;
use Psr\Log\LoggerInterface;
use Illuminate\Http\Client\Factory as HttpClient;

class BandwidthChannel
{
    /**
     * @source https://dev.bandwidth.com/messaging/about.html
     * @var string
     */
    public const API_BASE_URL = 'https://messaging.bandwidth.com/api/v2/';

    /**
     * @var HttpClient|\Illuminate\Support\Facades\Http
     */
    protected $client;

    /**
     * @var BandwidthConfig
     */
    protected $config;

    /**
     * @var LoggerInterface|\Illuminate\Support\Facades\Log
     */
    protected $logger;

    /***
     * @var Dispatcher
     */
    protected $events;

    /**
     * Create a new bandwidth channel instance.
     *
     * @param  HttpClient  $client
     * @param  BandwidthConfig  $config
     * @param  LoggerInterface  $logger
     * @param  Dispatcher  $dispatcher
     */
    public function __construct(
        HttpClient $client,
        BandwidthConfig $config,
        LoggerInterface $logger,
        Dispatcher $dispatcher
    ) {
        $this->client = $client;
        $this->config = $config;
        $this->logger = $logger;
        $this->events = $dispatcher;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     *
     * @return mixed
     * @throws \Exception
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

        $payload = $this->payload($message, $to);

        if ($this->config->dryRun()) {
            $this->logger->debug("Bandwidth Message-ID: <{$notification->id}>\n", $payload);

            return $payload;
        }

        return $this->sendMessage($notifiable, $notification, $payload);
    }

    /**
     * Call the API.
     *
     * @param  mixed  $notifiable
     * @param  Notification  $notification
     * @param  array  $payload
     *
     * @return mixed
     * @throws CouldNotSendException
     */
    protected function sendMessage($notifiable, Notification $notification, array $payload)
    {
        try {
            return $this->client
                ->withBasicAuth($this->config->getApiUsername(), $this->config->getApiPassword())
                ->withOptions([
                    'debug' => $this->config->debugHttp(),
                ])
                ->acceptJson()
                ->timeout(15)
                ->post($this->getPostUrl(), $payload)
                ->throw()
                ->json();
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

    protected function getPostUrl(): string
    {
        return self::API_BASE_URL."users/{$this->config->getAccountId()}/messages";
    }

    /**
     * Prepare the http payload.
     *
     * @param  BandwidthMessage  $message
     * @param  string  $to
     *
     * @return array
     */
    protected function payload(BandwidthMessage $message, string $to): array
    {
        return array_merge([
            'from' => $this->config->getFrom(),
            'to' => $to,
            'applicationId' => $this->config->getApplicationId(),
        ], $message->toArray());
    }
}
