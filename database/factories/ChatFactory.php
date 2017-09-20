<?php

use Faker\Generator as Faker;

$factory->define(App\Chat::class, function (Faker $faker) {
    return [
        'message' => $faker->text(),
        'read' => $faker->boolean(),
    ];
});
