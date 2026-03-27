<?php

return [
    'esewa' => [
        'merchant_id' => env('ESEWA_MERCHANT_ID', 'EPAYTEST'),
        'secret_key'  => env('ESEWA_SECRET_KEY', '8gBm/:&EnhH.1/q'),
        'sandbox'     => env('ESEWA_SANDBOX', true),
    ],
    'khalti' => [
        'secret_key' => env('KHALTI_SECRET_KEY', 'test_secret_key_dc74e0fd57cb46cd93832aee0a390234'),
        'public_key' => env('KHALTI_PUBLIC_KEY', 'test_public_key_dc74e0fd57cb46cd93832aee0a390234'),
        'sandbox'    => env('KHALTI_SANDBOX', true),
    ],
];
