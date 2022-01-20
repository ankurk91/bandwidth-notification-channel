<?php

namespace NotificationChannels\Bandwidth\Tests\Resources\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\Bandwidth\BandwidthMessage;

class TestNotificationWithCustomFrom extends Notification
{
    public function toBandwidth($notifiable)
    {
        return (new BandwidthMessage())
            ->content("Test message content.")
            ->from('+1987654320');
    }
}
