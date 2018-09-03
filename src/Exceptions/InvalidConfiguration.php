<?php

namespace NotificationChannels\Bandwidth\Exceptions;

use Exception;

class InvalidConfiguration extends Exception
{
    /**
     * @return InvalidConfiguration
     */
    public static function missingConfig()
    {
        return new static('In order to send notification via Bandwidth you need to add credentials in the `bandwidth` key of `config.services`.');
    }
}
