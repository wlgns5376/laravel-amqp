<?php

namespace Wlgns5376\LaravelAmqp;

class Consumer extends Client
{

    /**
     * @param callable $callback
     * @param array    $options
     */
    public function consume($callback, array $options = [])
    {
        try {
            $channel = $this->channel();
            $this->resolveOptions($options);

            if ($this->exchangable()) {
                $channel->exchange_declare(
                    $this->option('exchange'),
                    $this->option('exchange_type'),
                    $this->option('passive'),
                    $this->option('durable'),
                    $this->option('auto_delete')
                );
        
                list($queue_name, ,) = $channel->queue_declare(
                    $this->option('queue'),
                    $this->option('passive'),
                    $this->option('durable'),
                    $this->option('exclusive'),
                    $this->option('auto_delete')
                );
                
                foreach ($this->option('binding_key', []) as $binding_key) {
                    $channel->queue_bind($queue_name, $this->option('exchange'), $binding_key);
                }
            } else {
                $channel->queue_declare(
                    $queue_name = $this->option('queue'),
                    $this->option('passive'),
                    $this->option('durable'),
                    $this->option('exclusive'),
                    $this->option('auto_delete')
                );
            }

            $channel->basic_consume(
                $queue_name,
                $this->option('consumer_tag', ''),
                $this->option('no_local', false),
                $this->option('no_ask', false),
                $this->option('exclusive'),
                $this->option('nowait', false),
                $callback
            );
            
            while ($channel->is_open()) {
                $channel->wait();
            }

            $this->close();
        } catch (\Exception $e) {
            $this->close();

            throw $e;
        }        
    }
}