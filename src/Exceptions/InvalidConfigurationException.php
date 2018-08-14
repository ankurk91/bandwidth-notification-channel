<?php

namespace NotificationChannels\Bandwidth\Exceptions;

use Exception;

class InvalidConfigurationException extends Exception
{
    /**
     * @param  string $message
     * @return void
     */
    public function __construct($message = null)
    {
        $message = $message ?: 'In order to send notification via Bandwidth you need to add credentials in the `bandwidth` key of `config.services`.';
        parent::__construct($message);
    }
}
