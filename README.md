# Bandwidth Notification Channel for Laravel

[![Packagist](https://badgen.net/packagist/v/ankurk91/bandwidth-notification-channel)](https://packagist.org/packages/ankurk91/bandwidth-notification-channel)
[![GitHub tag](https://badgen.net/github/tag/ankurk91/bandwidth-notification-channel)](https://github.com/ankurk91/bandwidth-notification-channel/releases)
[![License](https://badgen.net/packagist/license/ankurk91/bandwidth-notification-channel)](LICENSE.txt)
[![Downloads](https://badgen.net/packagist/dt/ankurk91/bandwidth-notification-channel)](https://packagist.org/packages/ankurk91/bandwidth-notification-channel/stats)
[![tests](https://github.com/ankurk91/bandwidth-notification-channel/workflows/tests/badge.svg)](https://github.com/ankurk91/bandwidth-notification-channel/actions)
[![codecov](https://codecov.io/gh/ankurk91/bandwidth-notification-channel/branch/master/graph/badge.svg)](https://codecov.io/gh/ankurk91/bandwidth-notification-channel)

This package makes it easy to send [Bandwidth](https://www.bandwidth.com/messaging/sms-api/) SMS notifications with
Laravel.

## Installation

You can install the package via composer:

```bash
composer require ankurk91/bandwidth-notification-channel
```

Package will auto register the service provider.

## Setting up your Bandwidth account

* Grab your account credentials from [Bandwidth](https://dev.bandwidth.com/guides/accountCredentials.html)
* Add the account credentials in your `.env` file:

```dotenv
BANDWIDTH_ACCOUNT_ID=
BANDWIDTH_APPLICATION_ID=
BANDWIDTH_API_USERNAME=
BANDWIDTH_API_PASSWORD=
BANDWIDTH_FROM=
BANDWIDTH_DRY_RUN=false
```

## Publish the config file (optional)

You can publish the [config](./config/bandwidth.php) file into your project.

```bash
php artisan vendor:publish --provider="NotificationChannels\Bandwidth\BandwidthServiceProvider" --tag="config"
```

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

class AccountApproved extends Notification implements ShouldQueue
{
    use Queueable;
      
    public function via($notifiable)
    {
        return [BandwidthChannel::class];
    }
  
    public function toBandwidth($notifiable)
    {
        return BandwidthMessage::create()
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
      
    public function routeNotificationForBandwidth($notification)
    {
        return $this->phone_number;
    }
}
```

### Methods available on `BandwidthMessage` class

* `content()` - Accepts a string value for the notification body.
* `from()` - Accepts a phone number to use as the notification sender.
* `media()` - Accepts a URL or array of URLs to be used as MMS.
* `httpBody()` - Accepts an `array` to send along with notification http payload.

```php
<?php
use NotificationChannels\Bandwidth\BandwidthMessage;

BandwidthMessage::create()
            ->content("This is sample text message.")
            ->from('+19195551212')
            ->media([
                'https://example.com/a-public-image.jpg',
                'https://example.com/a-public-audio.mp3',
            ])
            ->httpBody([
                'tag' => 'info'         
            ]);
```

### Events

* The package utilises Laravel's inbuilt
  notification [events](https://laravel.com/docs/8.x/notifications#notification-events)
* You can listen to these events in your project's `EventServiceProvider` like:

```php
<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \Illuminate\Notifications\Events\NotificationSent::class => [
            \App\Listeners\NotificationSent::class,
        ],
        \Illuminate\Notifications\Events\NotificationFailed::class => [
            \App\Listeners\NotificationFailed::class,
        ],
    ];
    
    public function boot()
    {
        //
    }
}
```

Here is the example of failed event listener class
```php
<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Events\NotificationFailed;

class NotificationFailed implements ShouldQueue
{
    public function handle(NotificationFailed $event)
    {
        if ($event->channel !== \NotificationChannels\Bandwidth\BandwidthChannel::class) {
            return;
        }

        /** @var User $user */
        $user = $event->notifiable;
        
        // Do something
    }
}
```

### Notes (Taken from API docs)

* The `from` and `to` numbers must be in `E.164` format, for example `+19195551212`.
* Message content length must be `2048` characters or less.
* Messages larger than `160` characters will be automatically fragmented and re-assembled to fit within the `160`
  character transport constraints.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

```bash
composer test
```

## Security

If you discover any security related issues, please email `pro.ankurk1[at]gmail[dot]com` instead of using the issue
tracker.

### Resources

* Bandwidth API v2 [Docs](https://dev.bandwidth.com/messaging/about.html)
* Phone number
  validation [regex](https://stackoverflow.com/questions/6478875/regular-expression-matching-e-164-formatted-phone-numbers)
* [Supported MMS file types](https://dev.bandwidth.com/faq/messaging/mediaType.html)

## License

The [MIT](https://opensource.org/licenses/MIT) License.
