<?php

namespace NotificationChannels\Bandwidth\Exceptions;

use Throwable;

class BandwidthClientException extends BandwidthBaseException
{
    public function __construct(Throwable $exception)
    {
        parent::__construct($exception->getMessage(), $exception->getCode(), $exception);
    }
}
