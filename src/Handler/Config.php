<?php
/**
 * Created by PhpStorm.
 * User: cZhan
 * Date: 2017/11/4
 * Time: 14:40
 */

return [
    'default' => 'redis',

    'redis'=>[
        'default' => [
            'host'     => env('SMART_CACHE_REDIS_HOST'),
            'password' => env('SMART_CACHE_REDIS_PASSWORD'),
            'port'     => env('SMART_CACHE_REDIS_PORT'),
            'database' => env('SMART_CACHE_REDIS_DATABASE'),
        ],
        'column_redis' => [
            'host'     => env('COLUMN_CACHE_REDIS_HOST'),
            'password' => env('COLUMN_CACHE_REDIS_PASSWORD'),
            'port'     => env('COLUMN_CACHE_REDIS_PORT'),
            'database' => env('COLUMN_CACHE_REDIS_DATABASE'),
        ]
    ],

    'memcache'=>[
        'default' => [
            'host'     => 'localhost',
            'password' => null,
            'port'     => 6379,
            'database' => 0,
        ]
    ]

];