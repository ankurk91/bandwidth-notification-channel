# Changelog

## 3.0.0
* Requires Laravel v6.x+
* Test against php v7.4
* :warning: Rename config option `simulate` to `dry_run`

## 2.1.0
* Changed the API base URL to `https://messaging.bandwidth.com/api/v2`, according to [docs](https://dev.bandwidth.com/v2-messaging/) 
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
