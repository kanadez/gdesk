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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'paysystems' => [
        'enot' => [
            'base_uri' => env('ENOT_URL', 'https://enot.io/request/'),
            'api_key' => env('ENOT_API_KEY'),
            'email' => env('ENOT_EMAIL')
        ],
        'enot_p2p' => [
            'base_uri' => env('ENOT_P2P_URL', 'https://enot.io/request/'),
            'api_key' => env('ENOT_P2P_API_KEY'),
            'email' => env('ENOT_P2P_EMAIL')
        ],
        'lava' => [
            'base_uri' => env('LAVA_URL', 'https://api.lava.ru/'),
            'api_key' => env('LAVA_API_KEY'),
            'wallet_rub' => env('LAVA_WALLET_RUB'),
            'order_prefix' => env('LAVA_ORDER_PREFIX', 'LV_')
        ],
        'primepayments' => [
            'base_uri' => env('PRIMEPAYMENT_URL', 'https://pay.primepayments.ru/API/v1/'),
            'project_id' => env('PRIMEPAYMENT_PROJECT_ID'),
            'secret1' => env('PRIMEPAYMENT_SECRET_ONE'),
            'email' => env('PRIMEPAYMENT_EMAIL'),
        ],
        'primepayments2' => [
            'base_uri' => env('PRIMEPAYMENT2_URL', 'https://pay.primepayments.ru/API/v1/'),
            'project_id' => env('PRIMEPAYMENT2_PROJECT_ID'),
            'secret1' => env('PRIMEPAYMENT2_SECRET_ONE'),
            'email' => env('PRIMEPAYMENT2_EMAIL'),
        ],
        'interkassa' => [
            'base_uri' => env('INTERKASSA_API_URL', 'https://api.interkassa.com/v1/'),
            'login' => env('INTERKASSA_LOGIN'),
            'password' => env('INTERKASSA_KEY'),
            'business_account_id' => env('INTERKASSA_BUSINESS_ACCOUNT'),
            'wallet_rub' => env('INTERKASSA_WALLET_RUB'),
            'wallet_eur' => env('INTERKASSA_WALLET_EUR'),
        ],
        'qiwi' => [
            'base_uri' => env('QIWI_URL', 'https://edge.qiwi.com/'),
            'api_key' => env('QIWI_KEY'),
            'number' => env('QIWI_NUMBER')
        ],
        'coinbase' => [
            'base_uri' => env('COINBASE_URL', 'https://api.coinbase.com/v2/'),
            'api_key' => env('COINBASE_API_KEY'),
            'secret' => env('COINBASE_API_SECRET'),
            'accounts' => [
                'btc' => env('COINBASE_BTC_ACCOUNT'),
                'bch' => env('COINBASE_BCH_ACCOUNT'),
                'eth' => env('COINBASE_ETH_ACCOUNT'),
                'usdc' => env('COINBASE_USDC_ACCOUNT'),
                'ltc' => env('COINBASE_LTC_ACCOUNT'),
                'doge' => env('COINBASE_DOGE_ACCOUNT'),
            ]
        ],
        'stripe' => [
            'public_key' => env('STRIPE_PUBLIC_KEY'),
            'secret_key' => env('STRIPE_SECRET_KEY'),
            'webhook_key' => env('STRIPE_WEBHOOK_KEY')
        ],
        'binance' => [
            'base_uri' => env('BINANCE_URL'),
            'api_key' => env('BINANCE_API_KEY'),
            'api_secret' => env('BINANCE_API_SECRET'),
        ],
        'paypalych' => [
            'api_key' => env('PAYPALYCH_API_KEY'),
            'shop_id' => env('PAYPALYCH_SHOP_ID'),
        ]
    ]

];
