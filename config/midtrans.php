<?php 

return [
    'endpoints' => [
        'core' => [
            'charge' => env('MIDTRANS_BANK_TRANSFER_ENDPOINT'),
        ]
    ],
    'auth' => [
        'merchant_id' => env('MIDTRANS_MERCHANT_ID'),
        'client_key' => env('MIDTRANS_CLIENT_KEY'),
        'server_key' => env('MIDTRANS_SERVER_KEY')
    ],
    'payment_status' => [

    ],
];