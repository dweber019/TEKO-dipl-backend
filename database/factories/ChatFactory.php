<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Chat::class, function (Faker $faker) {
    return [
        'message' => $faker->text(),
        'read' => $faker->boolean(),
    ];
});
