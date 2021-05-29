<?php

namespace NotificationChannels\Bandwidth\Exceptions;

use RuntimeException;

class InvalidConfigException extends RuntimeException
{
    public static function missingConfig(): self
    {
        return new static('Bandwidth SMS configuration is missing in your `config/services.php` file.');
    }
}
