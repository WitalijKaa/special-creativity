<?php declare(strict_types=1);

return [

    'consumer_group_id' => env('KAFKA_CONSUMER_GROUP_ID', 'special_c'),
    'brokers' => env('KAFKA_BROKERS'),

    'securityProtocol' => env('KAFKA_SECURITY_PROTOCOL', 'PLAINTEXT'),

    'sasl' => [
        'mechanisms' => env('KAFKA_MECHANISMS', 'PLAINTEXT'),
        'username' => env('KAFKA_USERNAME', null),
        'password' => env('KAFKA_PASSWORD', null),
    ],

    'consumer_timeout_ms' => 4000,
    'offset_reset' => 'earliest', // "latest", "earliest" or "none" // starts from if new consumer or prev offset deleted

    'auto_commit' => false, // auto commit offset (in 'auto.commit.interval.ms') (((this is the same as 'enable.auto.commit')))

    'flush_timeout_in_ms' => 500,
    'flush_retries' => 17,
    'flush_retry_sleep_in_ms' => 2000,

    'cache_driver' => env('CACHE_STORE'),

    // KAFKA

    'sleep_on_error' => env('KAFKA_ERROR_SLEEP', 4),

    'partition' => env('KAFKA_PARTITION', 0),

    'compression' => env('KAFKA_COMPRESSION_TYPE', 'snappy'), // none , gzip , lz4 and snappy

    'debug' => env('KAFKA_DEBUG', false),

    'message_id_key' => 'special-creativity::msg',
];
