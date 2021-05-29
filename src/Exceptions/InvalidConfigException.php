<?php

namespace NotificationChannels\Bandwidth\Exceptions;

use Exception;

class InvalidConfigException extends Exception
{
    public static function missingConfig(): self
    {
        return new static('Bandwidth SMS configuration is missing in your `config/services.php` file.');
    }
}
