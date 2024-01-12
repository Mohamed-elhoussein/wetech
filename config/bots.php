<?php

return [
    'telegram' => [
        'active' => env('TELEGRAM_BOT', true),
        'bot_id' => env('TELEGRAM_BOT_ID'),
        'channels' => [
            'errors' => [
                'active' => env('TELEGRAM_ERRORS', true),
                'id' => env('TELEGRAM_ERRORS_CHANNEL')
            ],
            'backups' => [
                'active' => env('TELEGRAM_BACKUPS', false),
                'id' => env('TELEGRAM_BACKUPS_CHANNEL')
            ]
        ]
    ]
];
