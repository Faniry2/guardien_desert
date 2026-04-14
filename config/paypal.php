<?php
// config/paypal.php
// Généré automatiquement par srmklive/laravel-paypal
// php artisan vendor:publish --provider="Srmklive\PayPal\Providers\PayPalServiceProvider"

return [
    'mode'    => env('PAYPAL_MODE', 'sandbox'), // 'sandbox' ou 'live'
    'sandbox' => [
        'client_id'         => env('PAYPAL_SANDBOX_CLIENT_ID', ''),
        'client_secret'     => env('PAYPAL_SANDBOX_CLIENT_SECRET', ''),
        'app_id'            => '',
    ],
    'live' => [
        'client_id'         => env('PAYPAL_LIVE_CLIENT_ID', ''),
        'client_secret'     => env('PAYPAL_LIVE_CLIENT_SECRET', ''),
        'app_id'            => env('PAYPAL_LIVE_APP_ID', ''),
    ],
    'payment_action' => 'Sale',
    'currency'       => 'EUR',
    'billing_type'   => 'MerchantInitiatedBilling',
    'notify_url'     => '',
    'locale'         => 'fr_FR',
    'validate_ssl'   => true,
];
