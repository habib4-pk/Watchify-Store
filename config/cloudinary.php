<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cloudinary Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your Cloudinary settings. The CLOUDINARY_URL
    | environment variable is the primary way to configure credentials.
    |
    */

    'cloud_url' => env('CLOUDINARY_URL'),

    /*
    |--------------------------------------------------------------------------
    | Cloudinary Cloud Settings
    |--------------------------------------------------------------------------
    */
    'cloud' => [
        'cloud_name' => env('CLOUDINARY_CLOUD_NAME', ''),
        'api_key' => env('CLOUDINARY_API_KEY', ''),
        'api_secret' => env('CLOUDINARY_API_SECRET', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Cloudinary URL Settings
    |--------------------------------------------------------------------------
    */
    'url' => [
        'secure' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Upload Settings
    |--------------------------------------------------------------------------
    */
    'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET'),
    'notification_url' => env('CLOUDINARY_NOTIFICATION_URL'),
];
