<?php

$cfEnv = getenv('VCAP_SERVICES');
if ($cfEnv !== false) {
    try {
        $vcapServices = json_decode(getenv('VCAP_SERVICES'));
        $userProvided = head($vcapServices->{'user-provided'});

        $userProvidedConnection = null;

        foreach ($userProvided as $item) {
            if ($item->name === 'dipl-mailgun') {
                $userProvidedConnection = $item;
                break;
            }
        }

        if ($userProvidedConnection === null) {
            throw new Exception('No Service found for dipl-mailgun');
        }

        $_ENV['MAILGUN_DOMAIN'] = $userProvidedConnection->{'MAILGUN_DOMAIN'};
        $_ENV['MAILGUN_SECRET'] = $userProvidedConnection->{'MAILGUN_SECRET'};
    }
    catch (Exception $e) {
        dd($e->getMessage());
    }
}

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => $_ENV['MAILGUN_DOMAIN'] ?? env('MAILGUN_DOMAIN'),
        'secret' => $_ENV['MAILGUN_SECRET'] ?? env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\Models\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

];
