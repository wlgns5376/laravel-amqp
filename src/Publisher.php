<?php

namespace Wlgns5376\LaravelAmqp;

use PhpAmqpLib\Message\AMQPMessage;

class Publisher extends Client
{
    /**
     * @param string|mixed $message
     * 
     * @return \PhpAmqpLib\Message\AMQPMessage
     */
    protected function createMessage($message)
    {
        if (is_string($message)) {
            $body = $message;
        } else {
            $body = json_encode($message);
        }

        return new AMQPMessage($body);
    }

    /**
     * @param string|mixed $message
     * @param string|null  $routing_key
     * @param array        $options
     */
    public function publish($message, $routing_key = null, $options = [])
    {
        $this->resolveOptions($options);

        if ($this->exchangable()) {
            $this->channel()->exchange_declare(
                $this->option('exchange'),
                $this->option('exchange_type'),
                $this->option('passive'),
                $this->option('durable'),
                $this->option('auto_delete')
            );
        } else {
            $this->channel()->queue_declare(
                $routing_key = is_null($routing_key) ? $this->option('queue') : $routing_key,
                $this->option('passive'),
                $this->option('durable'),
                $this->option('exclusive'),
                $this->option('auto_delete')
            );
        }

        $this->channel()->basic_publish($this->createMessage($message), $this->option('exchange'), $routing_key);

        $this->close();
    }
}