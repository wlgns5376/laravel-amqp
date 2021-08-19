<?php

namespace Wlgns5376\LaravelAmqp;

use Illuminate\Support\ServiceProvider;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class AmqpServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/amqp.php', 'amqp'
        );

        $this->app->singleton('amqp', function($app) {
            $config = $app['config']['amqp'];
            return new AMQPStreamConnection(
                $config['host'],
                $config['port'],
                $config['user'],
                $config['password'],
                $config['vhost']
            );
        });

        $this->app->bind(Publisher::class, function($app) {
            return new Publisher($app['amqp'], $app['config']['amqp.options']);
        });

        $this->app->bind(Consumer::class, function($app) {
            return new Consumer($app['amqp'], $app['config']['amqp.options']);
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/amqp.php' => config_path('amqp.php')
        ], 'amqp');

        if ($this->app->runningInConsole()) {
            
        }
    }
}