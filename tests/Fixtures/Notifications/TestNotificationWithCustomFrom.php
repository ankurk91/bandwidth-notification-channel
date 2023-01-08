<?php
declare(strict_types=1);

namespace NotificationChannels\Bandwidth\Tests\Fixtures\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\Bandwidth\BandwidthMessage;

class TestNotificationWithCustomFrom extends Notification
{
    public function toBandwidth($notifiable): BandwidthMessage
    {
        return (new BandwidthMessage())
            ->content("Test message content.")
            ->from('+1987654320');
    }
}
