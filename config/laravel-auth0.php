<?php

return array(

    /*
    |--------------------------------------------------------------------------
    |   Your auth0 domain
    |--------------------------------------------------------------------------
    |   As set in the auth0 administration page
    |
    */

    'domain'        => env('AUTH0_DOMAIN', 'XXXX.auth0.com'),
    /*
    |--------------------------------------------------------------------------
    |   Your APP id
    |--------------------------------------------------------------------------
    |   As set in the auth0 administration page
    |
    */

    'client_id'     => env('AUTH0_CLIENT_ID', 'XXXX'),

    /*
    |--------------------------------------------------------------------------
    |   Your APP secret
    |--------------------------------------------------------------------------
    |   As set in the auth0 administration page
    |
    */
    'client_secret' => env('AUTH0_CLIENT_SECRET', 'XXXXX'),

   /*
    |--------------------------------------------------------------------------
    |   The redirect URI
    |--------------------------------------------------------------------------
    |   Should be the same that the one configure in the route to handle the
    |   'Auth0\Login\Auth0Controller@callback'
    |
    */

    'redirect_uri'  => 'http://127.0.0.1:8000/auth0/callback',

    /*
    |--------------------------------------------------------------------------
    |   Persistence Configuration
    |--------------------------------------------------------------------------
    |   persist_user            (Boolean) Optional. Indicates if you want to persist the user info, default true
    |   persist_access_token    (Boolean) Optional. Indicates if you want to persist the access token, default false
    |   persist_id_token        (Boolean) Optional. Indicates if you want to persist the id token, default false
    |
    */

    // 'persist_user' => true,
    // 'persist_access_token' => true,
    // 'persist_id_token' => true,

    /*
    |--------------------------------------------------------------------------
    |   The authorized token issuers
    |--------------------------------------------------------------------------
    |   This is used to verify the decoded tokens when using RS256
    |
    */
    'authorized_issuers'  => [ 'https://'.getenv('AUTH0_DOMAIN').'/' ],

    /*
    |--------------------------------------------------------------------------
    |   The authorized token audiences
    |--------------------------------------------------------------------------
    |
    */
    'api_identifier'  => env('AUTH0_AUDIENCE', ''),

    /*
    |--------------------------------------------------------------------------
    |   The secret format
    |--------------------------------------------------------------------------
    |   Used to know if it should decode the secret when using HS256
    |
    */
    // 'secret_base64_encoded'  => true,

    /*
    |--------------------------------------------------------------------------
    |   Supported algs by your API
    |--------------------------------------------------------------------------
    |   Algs supported by your API
    |
    */
    'supported_algs'        => ['RS256'],

);
