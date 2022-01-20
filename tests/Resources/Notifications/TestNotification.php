<?php

namespace NotificationChannels\Bandwidth\Tests\Resources\Notifications;

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
