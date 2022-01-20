<?php

namespace NotificationChannels\Bandwidth\Tests\Resources\Models;

use Illuminate\Notifications\Notifiable;

class TestNotifiableModelWithoutPhone
{
    use Notifiable;

    public function routeNotificationForBandwidth($notification)
    {
        return false;
    }

}
