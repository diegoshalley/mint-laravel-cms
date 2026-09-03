<?php

return [
    'max_upload_kb' => (int) env('MEDIA_MAX_UPLOAD_KB', 25600),
    'allowed_extensions' => ['pdf', 'docx', 'xlsx', 'jpg', 'jpeg', 'png', 'webp'],
    'image_extensions' => ['jpg', 'jpeg', 'png', 'webp'],
    'clamav' => [
        'executable' => env('CLAMAV_EXECUTABLE', 'clamdscan'),
        'timeout' => (int) env('CLAMAV_TIMEOUT', 60),
    ],
];
