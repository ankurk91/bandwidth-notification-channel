<?php
declare(strict_types=1);

namespace NotificationChannels\Bandwidth\Tests\Fixtures\Models;

use Illuminate\Notifications\Notifiable;

class TestNotifiableModel
{
    use Notifiable;

    public function routeNotificationForBandwidth($notification): string
    {
        return '+1234567890';
    }
}
