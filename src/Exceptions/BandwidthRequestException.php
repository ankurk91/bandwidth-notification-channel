<?php

namespace NotificationChannels\Bandwidth\Exceptions;

use Illuminate\Http\Client\Response;

class BandwidthRequestException extends BandwidthBaseException
{
    protected Response $response;

    public function __construct(Response $response)
    {
        $this->response = $response;

        parent::__construct($response->body(), $response->status());
    }

    public function getResponse(): Response
    {
        return $this->response;
    }
}
