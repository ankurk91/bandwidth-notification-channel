<?php

namespace NotificationChannels\Bandwidth\Tests\Resources\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\Bandwidth\BandwidthMessage;

class TestNotificationWithMedia extends Notification
{
    public function toBandwidth($notifiable): BandwidthMessage
    {
        return (new BandwidthMessage())
            ->content("Test message content.")
            ->media('http://localhost/image.png');
    }
}
