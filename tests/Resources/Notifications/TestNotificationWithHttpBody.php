<?php

namespace NotificationChannels\Bandwidth\Tests\Resources\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\Bandwidth\BandwidthMessage;

class TestNotificationWithHttpBody extends Notification
{
    public function toBandwidth($notifiable): BandwidthMessage
    {
        return BandwidthMessage::create()
            ->content("Test message content.")
            ->httpBody([
                'tag' => 'info',
            ]);
    }
}
