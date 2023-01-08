<?php
declare(strict_types=1);

namespace NotificationChannels\Bandwidth\Tests\Fixtures\Notifications;

use Illuminate\Notifications\Notification;

class TestNotificationWithoutMessageInstance extends Notification
{
    public function toBandwidth($notifiable): string
    {
        return 'Test message content.';
    }
}
