<?php

return [
    'live_url' => env('ARAMEX_LIVE_URL', "https://ws.aramex.net"),
    'dev_url' => env('ARAMEX_DEV_URL', "https://ws.sbx.aramex.net"),

    'mode' => env('ARAMEX_MODE', 'test'),

    'test' => [
        'country_code' => env('ARAMEX_COUNTRY_CODE'),
        'entity' => env('ARAMEX_ENTITY'),
        'number' => env('ARAMEX_NUMBER'),
        'pin' => env('ARAMEX_PIN'),
        'username' => env('ARAMEX_USERNAME'),
        'password' => env('ARAMEX_PASSWORD'),
    ],

    'live' => [
        'country_code' => env('ARAMEX_COUNTRY_CODE'),
        'entity' => env('ARAMEX_ENTITY'),
        'number' => env('ARAMEX_NUMBER'),
        'pin' => env('ARAMEX_PIN'),
        'username' => env('ARAMEX_USERNAME'),
        'password' => env('ARAMEX_PASSWORD'),
    ],

    'shipper' => [
        'name' => '',
        'email' => '',
        'mobile' => '',
        'company' => '',
        'address' => [
            'line1' => '',
            'line2' => ' ',
            'post_code' => '',
            'city' => '',
            'country_code' => '',
            'state_or_province_code' => ''
        ]
    ],

    'third_party' => [
        'name' => '',
        'email' => '',
        'mobile' => '',
        'company' => '',
        'address' => [
            'line1' => '',
            'line2' => '',
            'post_code' => '',
            'city' => '',
            'country_code' => '', //should be same account country code
            'state_or_province_code' => ''
        ]
    ],

    'kit' => [
        'height' => '',
        'width' => '',
        'length' => '',
        'weight' => ''
    ]
];
