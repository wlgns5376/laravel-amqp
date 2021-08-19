# Laravel AMQP Package

## Support
Laravel 8.0

## Install

### Install package
```sh
composer require wlgns5376/laravel-amqp
```

### Config publish
```sh
php artisan vendor:publish --tag=amqp
```

## Docker RabbitMQ
```sh
docker run -d --hostname my-rabbit --name some-rabbit -p 5672:5672 rabbitmq:3
```

## Configuration

### .env
```
AMQP_HOST=localhost
AMQP_PORT=5672
AMQP_USER=guest
AMQP_PASSWORD=guest
```

