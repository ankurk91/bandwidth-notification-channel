<?php
declare(strict_types=1);

namespace NotificationChannels\Bandwidth\Tests\Fixtures\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\Bandwidth\BandwidthMessage;

class TestNotification extends Notification
{
    public $id = 'random-id';

    public function toBandwidth($notifiable): BandwidthMessage
    {
        return BandwidthMessage::create('Test message content.');
    }
}
