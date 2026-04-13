<?php
// À ajouter dans config/services.php

return [
    // ... autres services

    'stripe' => [
        'key'            => env('STRIPE_KEY'),
        'secret'         => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],

    'paypal' => [
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'secret'    => env('PAYPAL_SECRET'),
        'mode'      => env('PAYPAL_MODE', 'sandbox'), // 'sandbox' ou 'live'
    ],
];

// ══ .env à configurer ══
/*
STRIPE_KEY=pk_live_xxxxx
STRIPE_SECRET=sk_live_xxxxx
STRIPE_WEBHOOK_SECRET=whsec_xxxxx

PAYPAL_CLIENT_ID=xxxxx
PAYPAL_SECRET=xxxxx
PAYPAL_MODE=live
*/
