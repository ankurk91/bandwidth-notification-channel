<?php
declare(strict_types=1);

namespace NotificationChannels\Bandwidth\Tests\Fixtures\Notifications;

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
