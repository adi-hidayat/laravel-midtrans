<?php 

return [
    'endpoints' => [
        'core' => [
            'charge' => env('MIDTRANS_CHARGE_ENDPOINT'),
            'token' => env('MIDTRANS_GET_TOKEN_ENDPOINT')
        ]
    ],
    'auth' => [
        'merchant_id' => env('MIDTRANS_MERCHANT_ID'),
        'client_key' => env('MIDTRANS_CLIENT_KEY'),
        'server_key' => env('MIDTRANS_SERVER_KEY')
    ],
    'payment_status' => [

    ],
    'response_messages' => [
        'BANK_TRANSFER_VIRTUAL_ACCOUNT_SUCCESS_MESSAGE' => '',
        'BANK_TRANSFER_ECHANNEL_SUCCESS_MESSAGE' => '',
        'BANK_TRANSFER_SUCCESS_MESSAGE' => '',
    ]
    
];