<?php
declare(strict_types=1);

namespace NotificationChannels\Bandwidth\Tests\Fixtures\Models;

use Illuminate\Notifications\Notifiable;

class TestNotifiableModelWithoutPhone
{
    use Notifiable;

    public function routeNotificationForBandwidth($notification): bool
    {
        return false;
    }

}
