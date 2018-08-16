# Bandwidth notification channel for Laravel

[![Packagist](https://img.shields.io/packagist/v/ankurk91/bandwidth-notification-channel.svg?style=flat-square)](https://packagist.org/packages/ankurk91/bandwidth-notification-channel)
[![GitHub tag](https://img.shields.io/github/tag/ankurk91/bandwidth-notification-channel.svg?style=flat-square)](https://github.com/ankurk91/bandwidth-notification-channel/releases)
[![License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Downloads](https://img.shields.io/packagist/dt/ankurk91/bandwidth-notification-channel.svg?style=flat-square)](https://packagist.org/packages/ankurk91/bandwidth-notification-channel)
[![StyleCI](https://styleci.io/repos/144573966/shield?branch=master)](https://styleci.io/repos/144573966)

This package makes it easy to send [Bandwidth](https://www.bandwidth.com/messaging/sms-api/) SMS notifications with Laravel v5.6.

## Installation
You can install the package via composer:
```
composer require ankurk91/bandwidth-notification-channel
```
Add the service provider in `config/app.php` file:  (optional)
```php
// config/app.php
'providers' => [
    //...
    NotificationChannels\Bandwidth\BandwidthServiceProvider::class,
],
```

## Setting up your Bandwidth account
* Add [Bandwidth](https://dev.bandwidth.com/security.html) service credentials to `config/services.php`
```php
// config/services.php

'bandwidth' => [
    'user_id' => env('BANDWIDTH_USER_ID'), 
    'api_token' => env('BANDWIDTH_API_TOKEN'), 
    'api_secret' => env('BANDWIDTH_API_SECRET'), 
    'from' => env('BANDWIDTH_FROM'), 
],
```
* The `from` option is the phone number that your SMS messages will be sent from. 
* The `from` number must be in E.164 format, like `+14244443192`. [Read more](https://dev.bandwidth.com/ap-docs/methods/messages/postMessages.html)

## Usage
Now you can use the Bandwidth channel in your `via()` method inside the notification class
```php
<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\Bandwidth\BandwidthChannel;
use NotificationChannels\Bandwidth\BandwidthMessage;

class AccountApproved extends Notification
{
    /**
     * Get the notification channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return [BandwidthChannel::class];
    }

    /**
     * Get the text representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return BandwidthMessage|string
     */
    public function toBandwidth($notifiable)
    {
        return (new BandwidthMessage())
            ->text("Hi {$notifiable->name}, Your account was approved!");
            //->from('+123456789'); // optional, will use global form when not set
            //->media('http://example.com/image-1.jpg'); // optional media url for MMS
    }
}
```

Add the `routeNotificationForBandwidth` method to your Notifiable model.
```php
<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    
    /**
     * Route notifications for the bandwidth channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string|boolean
     */
    public function routeNotificationForBandwidth($notification)
    {
        return $this->phone_number;
    }
}
```

## Testing
```
composer test
```

## License
The MIT License.
