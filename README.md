# Bandwidth notification channel for Laravel

[![Packagist](https://badgen.net/packagist/v/ankurk91/bandwidth-notification-channel)](https://packagist.org/packages/ankurk91/bandwidth-notification-channel)
[![GitHub tag](https://badgen.net/github/tag/ankurk91/bandwidth-notification-channel)](https://github.com/ankurk91/bandwidth-notification-channel/releases)
[![License](https://badgen.net/packagist/license/ankurk91/bandwidth-notification-channel)](LICENSE.txt)
[![Downloads](https://badgen.net/packagist/dt/ankurk91/bandwidth-notification-channel)](https://packagist.org/packages/ankurk91/bandwidth-notification-channel/stats)
[![Build Status](https://travis-ci.com/ankurk91/bandwidth-notification-channel.svg)](https://travis-ci.com/ankurk91/bandwidth-notification-channel)
[![codecov](https://codecov.io/gh/ankurk91/bandwidth-notification-channel/branch/master/graph/badge.svg)](https://codecov.io/gh/ankurk91/bandwidth-notification-channel)

This package makes it easy to send [Bandwidth](https://www.bandwidth.com/messaging/sms-api/) SMS notifications with Laravel v6.0+

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
Add the [Bandwidth](https://dev.bandwidth.com/security.html) service credentials in your `config/services.php` file:
```php
// config/services.php

'bandwidth' => [
    'application_id' => env('BANDWIDTH_APPLICATION_ID'), // required since v2
    'user_id' => env('BANDWIDTH_USER_ID'), 
    'api_token' => env('BANDWIDTH_API_TOKEN'), 
    'api_secret' => env('BANDWIDTH_API_SECRET'), 
    'from' => env('BANDWIDTH_FROM'), 
    'dry_run' => env('BANDWIDTH_DRY_RUN'), 
],
```
Also update your `.env.example` and `.env` files:
```
BANDWIDTH_APPLICATION_ID=
BANDWIDTH_USER_ID=
BANDWIDTH_API_TOKEN=
BANDWIDTH_API_SECRET=
BANDWIDTH_FROM=
BANDWIDTH_DRY_RUN=false
```
* The `from` option is the phone number that your messages will be sent from.
* The `dry_run` option allows to you test the channel without sending actual SMS. 
When `dry_run` is set to `true`,  messages will be written to your application's log files instead of being sent to the recipient.

## Usage
Now you can use the Bandwidth channel in the `via()` method inside your Notification class:
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
     * @return array|string|boolean
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
* `media()`: Accepts a URL or array of URLs to be used as MMS.
* `http()`: Accepts an `array` to send along with notification http payload.

### Events
* The package utilises Laravel's inbuilt notification [events](https://laravel.com/docs/6.x/notifications#notification-events)
* You can listen to these events in your app
    - `Illuminate\Notifications\Events\NotificationSent`
    - `Illuminate\Notifications\Events\NotificationFailed`

### Notes (Taken from API docs)
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
* Bandwidth API v2 [Docs](https://dev.bandwidth.com/messaging/about.html)
* Phone number validation [regex](https://stackoverflow.com/questions/6478875/regular-expression-matching-e-164-formatted-phone-numbers)

## License
The [MIT](https://opensource.org/licenses/MIT) License.
