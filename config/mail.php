<?php

return [
    'default' => env('MAIL_MAILER', 'smtp'),

    'mailers' => [
        'smtp' => [
            'transport' => 'smtp',
            // Laravel 12 uses a scheme; ssl in the lab corresponds to smtps.
            'scheme' => env('MAIL_SCHEME', env('MAIL_ENCRYPTION') === 'ssl' ? 'smtps' : null),
            'host' => env('MAIL_HOST', 'smtp.mail.ru'),
            'port' => env('MAIL_PORT', 465),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => 10,
            'local_domain' => env('MAIL_EHLO_DOMAIN', parse_url((string) env('APP_URL', 'http://localhost'), PHP_URL_HOST)),
        ],
    ],

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', env('MAIL_USERNAME')),
        'name' => env('MAIL_FROM_NAME', env('APP_NAME', 'night notes.')),
    ],
];
