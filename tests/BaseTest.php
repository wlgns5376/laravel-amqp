<?php

namespace Wlgns5376\LaravelAmqp\Tests;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use Wlgns5376\LaravelAmqp\Publisher;

class BaseTest extends TestCase
{
    /** @test */
    public function it_should_has_amqp_config()
    {
        $this->assertTrue(isset($this->app['config']['amqp']));
    }

    /** @test */
    public function it_should_has_amqp_singleton()
    {
        $this->assertTrue(isset($this->app['amqp']));
        $this->assertInstanceOf(AMQPStreamConnection::class, $this->app['amqp']);
    }

    /** @test */
    public function it_should_publisher_publish()
    {
        $this->app['config']->set('amqp.options.exchange', 'test2.topic');
        $this->app['config']->set('amqp.options.exchange_type', 'topic');

        $publisher = $this->app->make(Publisher::class);
        $publisher->publish('hello world', 'test.gretting');

        $this->assertTrue($publisher->option('exchange') == 'test2.topic');
    }

    /** @test */
    public function it_should_extend_exchange_publisher_publish()
    {
        $this->app['config']->set('amqp.options.exchange', 'test2.topic');
        $this->app['config']->set('amqp.options.exchange_type', 'topic');

        $publisher = $this->app->make(Publisher::class);
        $publisher->publish('hello world', 'test.gretting', [
            'exchange' => 'test3.topic'
        ]);

        $this->assertTrue($publisher->option('exchange') == 'test3.topic');
    }
}