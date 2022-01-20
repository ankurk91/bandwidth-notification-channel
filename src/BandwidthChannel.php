<?php
declare(strict_types=1);

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
     * @source https://dev.bandwidth.com/docs/messaging
     */
    protected const API_BASE_URL = 'https://messaging.bandwidth.com/api/v2/';

    public function __construct(
        protected HttpClient      $client,
        protected BandwidthConfig $config,
        protected LoggerInterface $logger,
        protected Dispatcher      $events
    )
    {
        //
    }

    public function send($notifiable, Notification $notification)
    {
        if (!$to = $notifiable->routeNotificationFor('bandwidth', $notification)) {
            return null;
        }

        $message = $this->getMessage($notification, $notifiable);
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
                ->acceptJson()
                ->timeout(30)
                ->withOptions($this->config->httpOptions())
                ->post($this->getPostUrl(), $payload)
                ->throw()
                ->json();
        } catch (RequestException $exception) {

            $this->emitFailedEvent($notifiable, $notification, $exception, $exception->response->json());
            throw new BandwidthRequestException($exception);
        } catch (Throwable $exception) {

            $this->emitFailedEvent($notifiable, $notification, $exception, $exception->getMessage());
            throw new BandwidthClientException($exception);
        }
    }

    protected function getPostUrl(): string
    {
        return self::API_BASE_URL . "users/{$this->config->getAccountId()}/messages";
    }

    protected function getMessage(Notification $notification, $notifiable): BandwidthMessage
    {
        if (!method_exists($notification, 'toBandwidth')) {
            throw new \RuntimeException('Notification class is missing toBandwidth method.');
        }

        $message = $notification->toBandwidth($notifiable);

        if (\is_string($message)) {
            $message = new BandwidthMessage($message);
        }

        return $message;
    }

    protected function getPayload(BandwidthMessage $message, string $to): array
    {
        return array_merge([
            'from' => $this->config->getFrom(),
            'to' => $to,
            'applicationId' => $this->config->getApplicationId(),
        ], $message->toArray());
    }

    protected function emitFailedEvent($notifiable, Notification $notification, Throwable $exception, $message): void
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
