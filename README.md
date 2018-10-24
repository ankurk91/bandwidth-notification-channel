# Bandwidth notification channel for Laravel

[![Packagist](https://img.shields.io/packagist/v/ankurk91/bandwidth-notification-channel.svg)](https://packagist.org/packages/ankurk91/bandwidth-notification-channel)
[![GitHub tag](https://img.shields.io/github/tag/ankurk91/bandwidth-notification-channel.svg)](https://github.com/ankurk91/bandwidth-notification-channel/releases)
[![License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE.md)
[![Downloads](https://img.shields.io/packagist/dt/ankurk91/bandwidth-notification-channel.svg)](https://packagist.org/packages/ankurk91/bandwidth-notification-channel/stats)
[![StyleCI](https://styleci.io/repos/144573966/shield?branch=master&style=plastic)](https://styleci.io/repos/144573966)
[![Build Status](https://travis-ci.com/ankurk91/bandwidth-notification-channel.svg)](https://travis-ci.com/ankurk91/bandwidth-notification-channel)
[![codecov](https://codecov.io/gh/ankurk91/bandwidth-notification-channel/branch/master/graph/badge.svg)](https://codecov.io/gh/ankurk91/bandwidth-notification-channel)

This package makes it easy to send [Bandwidth](https://www.bandwidth.com/messaging/sms-api/) SMS notifications with Laravel v5.6+

## Installation
You can install the package via composer:
```
composer require ankurk91/bandwidth-notification-channel:1.*
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
Add your [Bandwidth](https://dev.bandwidth.com/security.html) service credentials in `config/services.php` file:
```php
// config/services.php

'bandwidth' => [
    'user_id' => env('BANDWIDTH_USER_ID'), 
    'api_token' => env('BANDWIDTH_API_TOKEN'), 
    'api_secret' => env('BANDWIDTH_API_SECRET'), 
    'from' => env('BANDWIDTH_FROM'), 
    'simulate' => env('BANDWIDTH_SIMULATE'), 
],
```
Also update your `.env.example` and `.env` files:
```
BANDWIDTH_USER_ID=
BANDWIDTH_API_TOKEN=
BANDWIDTH_API_SECRET=
BANDWIDTH_FROM=
BANDWIDTH_SIMULATE=false
```
* The `from` option is the phone number that your messages will be sent from.
* The  `simulate` option allows to you test the channel without sending actual SMS. When set to `true`, it will write a log with http payload.

## Usage
Now you can use the Bandwidth channel in your `via()` method inside the notification class:
```php
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Bandwidth\BandwidthChannel;
use NotificationChannels\Bandwidth\BandwidthMessage;

class AccountApproved extends Notification
{
    use Queueable;
    
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
            ->content("Hi {$notifiable->name}, Your account was approved!");
    }
}
```

Add the `routeNotificationForBandwidth` method to your Notifiable model:
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

### Available `BandwidthMessage` methods
* `content()`: Accepts a string value for the notification body.
* `from()`: Accepts a phone number to use as the notification sender.
* `media()`: Accepts a URL or array of URLs to be used a MMS.
* `http()`: Accepts an `array` to send along with notification body; for eg: `callbackUrl`.

### Notes
* The `from` and `to` numbers must be in `E.164` format, for example `+14244443192`. 
* Message content length must be `2048` characters or less. Messages larger than `160` characters are automatically fragmented and re-assembled to fit within the `160` character transport constraints.

## Changelog
Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing
```
composer test
```

## Security
If you discover any security related issues, please email `pro.ankurk1[at]gmail[dot]com` instead of using the issue tracker.

### Resources
* Bandwidth [FAQ](https://dev.bandwidth.com/faq) for Developers
* Bandwidth [Docs](https://dev.bandwidth.com/ap-docs/methods/messages/postMessages.html) for Developers
* Phone number validation [regex](https://stackoverflow.com/questions/6478875/regular-expression-matching-e-164-formatted-phone-numbers)

## License
The [MIT](https://opensource.org/licenses/MIT) License.
