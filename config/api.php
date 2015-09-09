<?php

return [

    'prefix'    => env('API_PREFIX', 'api'),
    'namespace' => env('API_NAMESPACE', 'App\Api\Controllers'),
    'alias'     => env('API_ALIAS', 'api.'),

    'formats' => [

        'success' => [
            'status'   => ':status',
            'response' => ':response',
        ],

        'error' => [
            'status' => ':status',
            'error'  => [
                'message' => ':error-message',
            ],
        ],
    ],

    'mimes' => [
        'json' => 'application/json',
        'php-serialize' => 'text/plain',
    ],

    'adapters' => [
    ],

    'defaultAdapter' => env('API_DEFAULT_ADAPTER', 'json'),
    
];
