<?php

use Faker\Generator as Faker;

$factory->define(App\Notification::class, function (Faker $faker) {
    return [
        'message' => $faker->sentence(),
        'ref_id' => null,
        'ref' => null,
    ];
});
