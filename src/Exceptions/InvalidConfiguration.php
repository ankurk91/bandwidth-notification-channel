<?php

namespace NotificationChannels\Bandwidth\Exceptions;

class InvalidConfiguration extends \Exception
{
    public static function configurationNotSet()
    {
        return new static('In order to send notification via Bandwidth you need to add credentials in the `bandwidth` key of `config.services`.');
    }
}
