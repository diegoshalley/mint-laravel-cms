<?php

return [
    'default' => env('FILESYSTEM_DISK', 'local'),
    'disks' => [
        'local' => ['driver' => 'local', 'root' => storage_path('app/private'), 'throw' => false],
        'quarantine' => ['driver' => 'local', 'root' => storage_path('app/quarantine'), 'visibility' => 'private', 'throw' => true],
        'public_media' => ['driver' => 'local', 'root' => storage_path('app/public/media'), 'url' => env('APP_URL').'/storage/media', 'visibility' => 'public', 'throw' => true],
        's3' => [
            'driver' => 's3', 'key' => env('AWS_ACCESS_KEY_ID'), 'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'), 'bucket' => env('AWS_BUCKET'), 'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'), 'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false), 'throw' => false,
        ],
    ],
    'links' => [public_path('storage') => storage_path('app/public')],
];
