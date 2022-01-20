<?php

namespace NotificationChannels\Bandwidth\Tests\Resources\Notifications;

use Illuminate\Notifications\Notification;

class TestNotificationWithoutMessageInstance extends Notification
{
    public function toBandwidth($notifiable)
    {
        return 'Test message content.';
    }
}
