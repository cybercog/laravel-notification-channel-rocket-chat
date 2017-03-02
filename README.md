![cog-laravel-notification-rocket-chat](https://cloud.githubusercontent.com/assets/1849174/23518861/df6ee14e-ff85-11e6-8eea-3c4652ef82dd.png)

# Rocket.Chat notifications channel for Laravel 5.3+

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/rocket-chat.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/rocket-chat)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/rocket-chat/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/rocket-chat)
[![StyleCI](https://styleci.io/repos/:style_ci_id/shield)](https://styleci.io/repos/:style_ci_id)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/:sensio_labs_id.svg?style=flat-square)](https://insight.sensiolabs.com/projects/:sensio_labs_id)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/rocket-chat.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/rocket-chat)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/rocket-chat/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/rocket-chat/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/rocket-chat.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/rocket-chat)

## WIP

Don't use it on production. It's initial release. Stable version will be available at https://github.com/laravel-notification-channels/rocket-chat

## Introduction

This package makes it easy to send notifications using [RocketChat](https://rocket.chat/) with Laravel 5.3. 

## Contents

- [Installation](#installation)
	- [Setting up the RocketChat service](#setting-up-the-rocketchat-service)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [Change log](#changelog)
- [License](#license)


## Installation

You can install the package via composer:

```sh
composer require laravel-notification-channels/rocket-chat
```

You must install the service provider:

```php
// config/app.php
'providers' => [
    ...
    NotificationChannels\HipChat\HipChatServiceProvider::class,
],
```
### Setting up the RocketChat service

In order to send message to RocketChat channels, you need to obtain [Webhook](https://rocket.chat/docs/administrator-guides/integrations#how-to-create-a-new-incoming-webhook).

Add your RocketChat API server's base url, incoming Webhook Token and optionally the default room to your `config/services.php`:

```php
// config/services.php
...
'rocketchat' => [
     // Base URL for RocketChat API server (https://your.rocketchat.server.com)
    'url' => env('ROCKETCHAT_URL'),
    'token' => env('ROCKETCHAT_TOKEN'),
    // Default room (optional)
    'room' => env('ROCKETCHAT_ROOM'),
],
...
```

## Usage

You can use the channel in your `via()` method inside the notification:

```php
use Illuminate\Notifications\Notification;
use NotificationChannels\RocketChat\RocketChatMessage;
use NotificationChannels\RocketChat\RocketChatChannel;

class TaskCompleted extends Notification
{
    public function via($notifiable)
    {
        return [RocketChatChannel::class];
    }

    public function toRocketChat($notifiable)
    {
        return RocketChatMessage::create("Test message")
            ->to('room_id') // optional
            ->from('webhook_token');
    }
}
```

In order to let your notification know which RocketChat room you are targeting, add the `routeNotificationForRocketChat` method to your Notifiable model:

```php
public function routeNotificationForRocketChat()
{
    return 'room_id';
}
```

### Available methods

`from()`: Sets the sender's access token.

`to()`: Specifies the room id to send the notification to (overridden by `routeNotificationForRocketChat` if empty).

`content()`: Sets a content of the notification message. Supports Github flavoured markdown.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

```sh
$ composer test
```

## Security

If you discover any security related issues, please email a.komarev@cybercog.su instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Anton Komarev](https://github.com/a-komarev)
- [All Contributors](../../contributors)

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## About CyberCog

[CyberCog](http://www.cybercog.ru) is a Social Unity of enthusiasts. Research best solutions in product & software development is our passion.

![cybercog-logo](https://cloud.githubusercontent.com/assets/1849174/18418932/e9edb390-7860-11e6-8a43-aa3fad524664.png)
