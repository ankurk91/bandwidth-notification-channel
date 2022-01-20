<?php

namespace NotificationChannels\Bandwidth\Tests\Resources\Models;

use Illuminate\Notifications\Notifiable;

class TestNotifiableModel
{
    use Notifiable;

    public function routeNotificationForBandwidth($notification)
    {
        return '+1234567890';
    }
}
