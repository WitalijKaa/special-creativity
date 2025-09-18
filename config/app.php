<?php

return [

    'name' => env('APP_NAME'),
    'env' => env('APP_ENV'),
    'debug' => (bool)env('APP_DEBUG'),
    'url' => env('APP_URL'),

    'timezone' => 'Europe/Belgrade',
    'locale' => env('APP_LOCALE'),
    'fallback_locale' => env('APP_FALLBACK_LOCALE'),
    'faker_locale' => env('APP_FAKER_LOCALE'),

    'cipher' => 'AES-256-CBC',
    'key' => env('APP_KEY'),
    'previous_keys' => [],

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

];
