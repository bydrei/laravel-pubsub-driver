# Laravel Google Cloud pubsub Driver

[![Latest Version](https://img.shields.io/github/release/bydrei/laravel-pubsub-driver.svg?style=flat-square)](https://github.com/bydrei/laravel-pubsub-driver/releases)
[![Issues Open](https://img.shields.io/github/issues/bydrei/laravel-pubsub-driver)](https://github.com/bydrei/laravel-pubsub-driver/issues)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Total Downloads](https://img.shields.io/packagist/dt/bydrei/laravel-pubsub-drive.svg?style=flat-square)](https://packagist.org/packages/bydrei/laravel-pubsub-drive)

## Introduction

The aim of this package is to provide developers a Laravel queue driver to support google cloud pubsub.
This is still in early development but it already does the following:

1. Sends messages to a queue (topic)
2. Processes messages in a queue (subscriber)

## Installation

Install the package

```bash
composer require bydrei/laravel-pubsub-drive 1.0.0
```

Publish the service provider

```bash
php artisan vendor:publish 
```

Example of .env file configuration

```bash
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=

LOG_CHANNEL=errorlog

# Google Pub/Sub
GOOGLE_CLOUD_PROJECT_ID=[GOOGLE_PROJECT_ID]
GOOGLE_APPLICATION_CREDENTIALS=/path_to_google_credentials.json

QUEUE_CONNECTION=pubsub
QUEUE_DRIVER=pubsub
```

## Testing

``` bash
$ phpunit
```

## Contributing

TBC

## License

The MIT License (MIT). Please see [License File](https://github.com/bydrei/laravel-pubsub-driver/develop/LICENSE) for more information.