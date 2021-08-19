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