<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    'espocrm' => [
 
        // URL de base de votre instance EspoCRM (sans slash final)
        'url'        => env('ESPOCRM_URL', ''),
 
        // Clé API de l'API User EspoCRM
        'api_key'    => env('ESPOCRM_API_KEY', ''),
 
        // Clé secrète HMAC (laisser vide pour n'utiliser que l'API Key)
        'secret_key' => env('ESPOCRM_SECRET_KEY', null),
 
        // Timeout HTTP en secondes
        'timeout'    => env('ESPOCRM_TIMEOUT', 10),
    ],

];
