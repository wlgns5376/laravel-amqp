<?php

namespace Wlgns5376\LaravelAmqp;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Client
{
    /**
     * @var \PhpAmqpLib\Connection\AMQPStreamConnection
     */
    protected $connection;

    /**
     * @var array
     */
    protected $options = [
        'queue' => '',
        'exchange' => '',
        'exchange_type' => 'direct',
        'passive' => false,
        'durable' => false,
        'auto_delete' => true,
        'exclusive' => false,
        'binding_key' => [],
    ];

    /**
     * @param AMQPStreamConnection $connection
     * @param array                $options
     */
    public function __construct(AMQPStreamConnection $connection, array $options)
    {
        $this->connection = $connection;
        $this->resolveOptions($options);
    }

    /**
     * @return \PhpAmqpLib\Channel\AMQPChannel
     */
    public function channel()
    {
        return $this->connection->channel();
    }

    public function close()
    {
        $this->channel()->close();
        $this->connection->close();
    }

    /**
     * @param array $options
     * 
     * @return Client
     */
    protected function resolveOptions(array $options)
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }

    /**
     * @param string $key
     * @param string $default
     * 
     * @return mixed
     */
    public function option($key, $default = null)
    {
        return isset($this->options[$key]) ? $this->options[$key] : $default;
    }

    /**
     * @return boolean
     */
    protected function exchangable()
    {
        return empty($this->option('exchange')) === false;
    }
}