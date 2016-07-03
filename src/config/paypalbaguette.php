<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Paypal client id
    |--------------------------------------------------------------------------
    |
    | Here you may specify your client id that should be used
    | by the api. If you don't have one then go on paypal developer and create 
    | a developer account ! if you work with env file, then set 
    | CLIENT_ID in there.
    |
    */

    'client_id' => env('CLIENT_ID', 'your-client-id'),



    /*
    |--------------------------------------------------------------------------
    | Paypal secret id
    |--------------------------------------------------------------------------
    |
    | Here you may specify your client secret that should be used
    | by the api. If you don't have one then go on paypal developer and create 
    | a developer account ! if you work with env file, then set  
    | CLIENT_SECRET in there.
    |
    */
    'client_secret' => env('ClIENT_SECRET', 'your-client-secret'),


    /*
    |--------------------------------------------------------------------------
    | Paypal env
    |--------------------------------------------------------------------------
    |
    | Here you may specify your env that should be used
    | by the api. If you set it to sandbox, then the sandbox mod will
    | be activated
    |
    */
    'env' => env('PAYPAL_ENV', 'sandbox'),

];