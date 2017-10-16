<?php

$cfEnv = getenv('VCAP_SERVICES');
if ($cfEnv !== false) {
    try {
        $vcapServices = json_decode(getenv('VCAP_SERVICES'));
        $userProvided = head($vcapServices->{'user-provided'});

        $userProvidedConnection = null;

        foreach ($userProvided as $item) {
            if ($item->name === 'dipl-auth0') {
                $userProvidedConnection = $item;
                break;
            }
        }

        if ($userProvidedConnection === null) {
            throw new Exception('No Service found for dipl-auth0');
        }

        $_ENV['AUTH0_CLIENT_ID'] = $userProvidedConnection->{'AUTH0_CLIENT_ID'};
        $_ENV['AUTH0_CLIENT_SECRET'] = $userProvidedConnection->{'AUTH0_CLIENT_SECRET'};
        $_ENV['AUTH0_DOMAIN'] = $userProvidedConnection->{'AUTH0_DOMAIN'};
        $_ENV['AUTH0_AUDIENCE'] = $userProvidedConnection->{'AUTH0_AUDIENCE'};
        $_ENV['AUTH0_CALLBACK_URL'] = $userProvidedConnection->{'AUTH0_CALLBACK_URL'};
    }
    catch (Exception $e) {
        dd($e->getMessage());
    }
}

return array(

    /*
    |--------------------------------------------------------------------------
    |   Your auth0 domain
    |--------------------------------------------------------------------------
    |   As set in the auth0 administration page
    |
    */

    'domain'        => $_ENV['AUTH0_DOMAIN'] ?? getenv('AUTH0_DOMAIN'),
    /*
    |--------------------------------------------------------------------------
    |   Your APP id
    |--------------------------------------------------------------------------
    |   As set in the auth0 administration page
    |
    */

    'client_id'     => $_ENV['AUTH0_CLIENT_ID'] ?? getenv('AUTH0_CLIENT_ID'),

    /*
    |--------------------------------------------------------------------------
    |   Your APP secret
    |--------------------------------------------------------------------------
    |   As set in the auth0 administration page
    |
    */
    'client_secret' => $_ENV['AUTH0_CLIENT_SECRET'] ?? getenv('AUTH0_CLIENT_SECRET'),

   /*
    |--------------------------------------------------------------------------
    |   The redirect URI
    |--------------------------------------------------------------------------
    |   Should be the same that the one configure in the route to handle the
    |   'Auth0\Login\Auth0Controller@callback'
    |
    */

    'redirect_uri'  => $_ENV['AUTH0_CALLBACK_URL'] ?? getenv('AUTH0_CALLBACK_URL'),

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
    'authorized_issuers'  => [ 'https://' . ($_ENV['AUTH0_DOMAIN'] ?? getenv('AUTH0_DOMAIN')) . '/' ],

    /*
    |--------------------------------------------------------------------------
    |   The authorized token audiences
    |--------------------------------------------------------------------------
    |
    */
    'api_identifier'  => $_ENV['AUTH0_AUDIENCE'] ?? getenv('AUTH0_AUDIENCE'),

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
