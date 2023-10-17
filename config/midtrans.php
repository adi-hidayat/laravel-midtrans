<?php 

return [
    'endpoints' => [
        'core' => [
            'charge'    => env('MIDTRANS_CHARGE_ENDPOINT'),
            'token'     => env('MIDTRANS_TOKEN_ENDPOINT'),
            'capture'   => env('MIDTRANS_CAPTURE_ENDPOINT'),
            'cancel'    => env('MIDTRANS_CANCEL_ENDPOINT'),
            'expire'    => env('MIDTRANS_EXPIRE_ENDPOINT'),
            'refund'    => env('MIDTRANS_REFUND_ENDPOINT'),
            'status'    => env('MIDTRANS_STATUS_ENDPOINT')
        ]
    ],

    'auth' => [
        'merchant_id'   => env('MIDTRANS_MERCHANT_ID'),
        'client_key'    => env('MIDTRANS_CLIENT_KEY'),
        'server_key'    => env('MIDTRANS_SERVER_KEY')
    ],
    
    'payment_status' => [

    ],

    'status_code' => [
        200 => 'Success',
        201 => 'Pending',
        202 => 'Denied',
        300 => 'Move Permanently',
        400 => 'Validation Error',
        401 => 'Unauthorized',
        402 => 'No access for this payment type',
        403 => 'The requested resource is not capable'
    ],
    
    'response_messages' => [
        'BANK_TRANSFER_VIRTUAL_ACCOUNT_SUCCESS' => '',
        'BANK_TRANSFER_ECHANNEL_SUCCESS'        => '',
        'BANK_TRANSFER_SUCCESS'                 => '',
        'CREDIT_CARD_SUCCESS'                   => '',
    ]
    
];