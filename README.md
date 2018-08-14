# Bandwidth notification channel for Laravel

[![Packagist](https://img.shields.io/packagist/v/ankurk91/bandwidth-notification-channel.svg?style=flat-square)](https://packagist.org/packages/ankurk91/bandwidth-notification-channel)
[![License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Downloads](https://img.shields.io/packagist/dt/ankurk91/bandwidth-notification-channel.svg?style=flat-square)](https://packagist.org/packages/ankurk91/bandwidth-notification-channel)
[![StyleCI](https://styleci.io/repos/144573966/shield?branch=master)](https://styleci.io/repos/144573966)

This package makes it easy to send [Bandwidth](https://www.bandwidth.com/messaging/sms-api/) sms notifications with Laravel v5.6.

## Installation
You can install the package via composer:
```
composer require ankurk91/bandwidth-notification-channel
```
Add the service provider to `config/app.php` `providers` array (optional)
```php
// config/app.php
'providers' => [
    ...
    NotificationChannels\Bandwidth\BandwidthServiceProvider::class,
],
```

## Setting up your Bandwidth account
* Add [Bandwidth](https://app.bandwidth.com/) SMS service credentials to `config/services.php`
```php
// config/services.php

'bandwidth' => [
    'user_id' => env('BANDWIDTH_USER_ID'), 
    'api_token' => env('BANDWIDTH_API_TOKEN'), 
    'api_secret' => env('BANDWIDTH_API_SECRET'), 
    'from' => env('BANDWIDTH_FROM'), 
],
```
* Note that `from` must be in E.164 format, like `+14244443192`

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
     * @return BandwidthMessage
     */
    public function toBandwidth($notifiable)
    {
        return (new BandwidthMessage())
            ->text("Hi {$notifiable->name}, Your account was approved!");
            //->from('+123456789'); // optional, will use global config when not set
            //->media('http://example.com/image-1.jpg'); // optional MMS
    }
}
```

In order to let your Notification know which phone number are you sending to, the channel will look for the `phone_number` attribute of the Notifiable model. 
If you want to override this behaviour, add the `routeNotificationForBandwidth` method to your Notifiable model.
```php
// app/User.php
public function routeNotificationForBandwidth()
{
    return $this->primary_phone;
}
```

## Testing
```
composer test
```

## License
The MIT License.
