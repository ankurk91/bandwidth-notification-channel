<?php

namespace NotificationChannels\Bandwidth\Exceptions;

use Exception;
use Throwable;

class CouldNotSendException extends Exception
{
    public static function clientError(Throwable $exception)
    {
        return new static(
            $exception->getMessage(),
            $exception->getCode(),
            $exception
        );
    }
}
