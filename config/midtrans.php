<?php 

return [
    'auth' => [
        'merchant_id'       => env('MIDTRANS_MERCHANT_ID'),
        'client_key'        => env('MIDTRANS_CLIENT_KEY'),
        'server_key'        => env('MIDTRANS_SERVER_KEY'),
        'secured_request'   => false,
        'x_payment_token'   => env('X_PAYMENT_TOKEN') // set token if secured request is true
    ],
    'endpoints' => [
        'core' => [
            'charge'    => env('MIDTRANS_CHARGE_ENDPOINT'),
            'token'     => env('MIDTRANS_TOKEN_ENDPOINT'),
            'capture'   => env('MIDTRANS_CAPTURE_ENDPOINT'),
            'cancel'    => env('MIDTRANS_CANCEL_ENDPOINT'),
            'expire'    => env('MIDTRANS_EXPIRE_ENDPOINT'),
            'refund'    => env('MIDTRANS_REFUND_ENDPOINT'),
            'status'    => env('MIDTRANS_STATUS_ENDPOINT')
        ],
        'snap' => [
            'transaction' => env('MIDTRANS_SNAP_ENDPOINT')
        ]
    ],
    'payment_methods' => [
        'BANK_TRANSFER'     => 'Bank Transfer',
        'CREDIT_CARD'       => 'Credit Card',
        'EWALLET'           => 'Ewallet',
        'OVER_THE_COUNTER'  => 'Over The Counter',
        'CARDLESS_CREDIT'   => 'Cardless Credit',
    ]
];