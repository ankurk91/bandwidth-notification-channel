# Changelog

## [6.3.0](https://github.com/ankurk91/bandwidth-notification-channel/compare/6.2.0..6.3.0)

* Add support for Laravel 10

## [6.2.0](https://github.com/ankurk91/bandwidth-notification-channel/compare/6.1.0..6.2.0)

* Drop support for Laravel v8
* Drop support for php v8.0
* Test on php v8.2

## [6.1.0](https://github.com/ankurk91/bandwidth-notification-channel/compare/6.0.0..6.1.0)

* Allow Laravel v9.x
* Drop support for php `7.4`
* Default HTTP timeout is `30` second now

## [6.0.0](https://github.com/ankurk91/bandwidth-notification-channel/compare/5.0.1..6.0.0)

* Configuration has been moved from `config/services.php` to `config/bandwidth.php`
* Drop support for Laravel 7.x
* Rename `http()` method to `httpBody()`
* Allow to override guzzle http options via config

## [5.0.0](https://github.com/ankurk91/bandwidth-notification-channel/compare/4.0.3..5.0.0)

* Requires php v7.4+
* :warning: Environment variable names has been changed
* Remove retry from http client, your queue worker should handle this
* Report exceptions with full api response body
* Exception class names has been changed

## [4.0.0](https://github.com/ankurk91/bandwidth-notification-channel/compare/3.0.1..4.0.0)

* Requires Laravel v7.7+
* Requires php v7.3+
* `BandwidthClient` class has been removed in favor of Laravel
  inbuilt [HTTP client](https://laravel.com/docs/7.x/http-client)

## 3.0.0

* Requires Laravel v6.x+
* Test against php v7.4
* :warning: Rename config option `simulate` to `dry_run`

## 2.1.0

* Changed the API base URL to `https://messaging.bandwidth.com/api/v2`, according
  to [docs](https://dev.bandwidth.com/v2-messaging/)
  the old URL will be deprecated after June 13th, 2019. Please update your apps to use the new URL as soon as possible.

## 2.0.0

* Upgrade Bandwidth API version to 2.0
* Read the documentation before upgrading

### 1.6.0

* Add: throw custom exception on failure
* Add: dispatch `NotificationFailed` event before throwing exception
* Add: log Notification id in simulation

### 1.5.0

* Add `simulate` option

### 1.4.0

* Remove undocumented phone number validation rule

## 1.1.0

* Add `http()` method to `BandwidthMessage` class

## 1.0.0

* Initial release
