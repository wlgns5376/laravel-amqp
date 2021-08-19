# Laravel AMQP Package
라라벨에서 RabbitMQ Publish/Consume을 사용하기 위한 패키지 입니다.

## Support
- Laravel 8
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

### Environment

`.env`
```
AMQP_HOST=localhost
AMQP_PORT=5672
AMQP_USER=guest
AMQP_PASSWORD=guest
AMQP_VHOST=/
```

### Config

`config/amqp.php`
```php
<?php
return [
    'host'     => env('AMQP_HOST', 'localhost'),
    'port'     => env('AMQP_PORT', 5672),
    'user'     => env('AMQP_USER', 'guest'),
    'password' => env('AMQP_PASSWORD', 'guest'),
    'vhost'    => env('AMQP_VHOST', '/'),
    'options'  => [
        'queue'         => '',
        'exchange'      => '',
        'exchange_type' => 'direct',
        'consumer_tag'  => '',
        'passive'       => false,
        'durable'       => false,
        'auto_delete'   => true,
        'exclusive'     => false,
        'binding_key'   => [],
    ],
];
```

## Topic

예제 참고: [RabbitMQ Topic - PHP](https://www.rabbitmq.com/tutorials/tutorial-five-php.html)

`config/amqp.php`
```php
<?php
return [
    ...
    'options'  => [
        'queue'         => '',
        'exchange'      => 'topic_logs',
        'exchange_type' => 'topic',
        ...
        'binding_key'   => [
            'kern.*',
            '*.critical'
        ],
    ],
];
```

### Job으로 메세지 전달

`app/Jobs/SampleJob.php`
```php
<?php

namespace App\Jobs;

...
use Wlgns5376\LaravelAmqp\Publisher;

class SampleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Publisher $publisher)
    {
        $publisher->publish('A critical kernel error', 'kern.critical');
    }
}

```
#### Option 확장

```php
$publisher->publish('A critical kernel error', 'kern.critical', [
    'exchange' => 'other_topic_logs',
    'durable' => true,
]);
```

### Command로 메세지 수신

`app/Console/Commands/ConsumeCommand.php`
```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Wlgns5376\LaravelAmqp\Consumer;

class ConsumeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'amqp:consume';

    /**
     * Execute the console command.
     * 
     * @param Wlgns5376\LaravelAmqp\Consumer $consumer
     *
     * @return int
     */
    public function handle(Consumer $consumer)
    {
        $consumer->consume(function($message) {
            echo ' [x] ', $message->delivery_info['routing_key'], ':', $message->body, "\n";
        });

        return 0;
    }
}

```

#### Option 확장
```php
$consumer->consume(function($message) {
    echo ' [x] ', $message->delivery_info['routing_key'], ':', $message->body, "\n";
}, [
    'exchange' => 'other_topic_logs',
    'durable' => true,
]);
```