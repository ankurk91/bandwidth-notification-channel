<?php
declare(strict_types=1);

namespace NotificationChannels\Bandwidth\Exceptions;

use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;

class BandwidthRequestException extends BandwidthBaseException
{
    protected Response $response;

    public function __construct(RequestException $exception)
    {
        $this->response = $exception->response;

        parent::__construct($this->response->body(), $this->response->status(), $exception);
    }

    public function getResponse(): Response
    {
        return $this->response;
    }
}
