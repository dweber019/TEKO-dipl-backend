<?php

$cfEnv = getenv('VCAP_SERVICES');
if ($cfEnv !== false) {
    try {
        $vcapServices = json_decode(getenv('VCAP_SERVICES'));
        $dynConnection = head($vcapServices->dynstrg)->credentials;

        $_ENV['AWS_KEY'] = $dynConnection->accessKey;
        $_ENV['AWS_SECRET'] = $dynConnection->sharedSecret;
        $_ENV['AWS_REGION'] = 'eu-west-1';
        $_ENV['AWS_BUCKET'] = 'backend';
        $_ENV['AWS_ENDPOINT'] = 'https://' . $dynConnection->accessHost;
    }
    catch (Exception $e) {
        dd($e->getMessage());
    }
}

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "s3", "rackspace"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => $_ENV['AWS_KEY'] ?? env('AWS_KEY'),
            'secret' => $_ENV['AWS_SECRET'] ?? env('AWS_SECRET'),
            'region' => $_ENV['AWS_REGION'] ?? env('AWS_REGION'),
            'bucket' => $_ENV['AWS_BUCKET'] ?? env('AWS_BUCKET'),
            'endpoint' => $_ENV['AWS_ENDPOINT'] ?? env('AWS_ENDPOINT'),
        ],

    ],

];
