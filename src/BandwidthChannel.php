<?php

namespace NotificationChannels\Bandwidth;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Client\Factory as HttpClient;
use Illuminate\Http\Client\RequestException;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Notification;
use NotificationChannels\Bandwidth\Exceptions\BandwidthClientException;
use NotificationChannels\Bandwidth\Exceptions\BandwidthRequestException;
use Psr\Log\LoggerInterface;
use Throwable;

class BandwidthChannel
{
    /**
     * @source https://dev.bandwidth.com/messaging/about.html
     */
    protected const API_BASE_URL = 'https://messaging.bandwidth.com/api/v2/';

    protected HttpClient $client;

    protected BandwidthConfig $config;

    protected LoggerInterface $logger;

    protected Dispatcher $events;

    public function __construct(
        HttpClient $client,
        BandwidthConfig $config,
        LoggerInterface $logger,
        Dispatcher $dispatcher
    )
    {
        $this->client = $client;
        $this->config = $config;
        $this->logger = $logger;
        $this->events = $dispatcher;
    }

    public function send($notifiable, Notification $notification)
    {
        if (!$to = $notifiable->routeNotificationFor('bandwidth', $notification)) {
            return null;
        }

        $message = $notification->toBandwidth($notifiable);

        if (\is_string($message)) {
            $message = new BandwidthMessage($message);
        }

        $payload = $this->getPayload($message, $to);

        if ($this->config->dryRun()) {
            $this->logger->debug("Bandwidth Message-ID: <{$notification->id}>\n", $payload);

            return $payload;
        }

        return $this->sendMessage($notifiable, $notification, $payload);
    }

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
        } catch (RequestException $exception) {

            $this->emitFailedEvent($notifiable, $notification, $exception, $exception->response->json());
            throw new BandwidthRequestException($exception->response);
        } catch (Throwable $exception) {

            $this->emitFailedEvent($notifiable, $notification, $exception, $exception->getMessage());
            throw new BandwidthClientException($exception);
        }
    }

    protected function getPostUrl(): string
    {
        return self::API_BASE_URL . "users/{$this->config->getAccountId()}/messages";
    }

    protected function getPayload(BandwidthMessage $message, string $to): array
    {
        return array_merge([
            'from' => $this->config->getFrom(),
            'to' => $to,
            'applicationId' => $this->config->getApplicationId(),
        ], $message->toArray());
    }

    protected function emitFailedEvent($notifiable, Notification $notification, Throwable $exception, $message)
    {
        $this->events->dispatch(new NotificationFailed(
            $notifiable,
            $notification,
            self::class,
            [
                'message' => $message,
                'exception' => $exception,
            ]
        ));
    }
}
