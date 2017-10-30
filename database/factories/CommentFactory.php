<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Comment::class, function (Faker $faker) {
    return [
        'message' => $faker->text(),
        'user_id' => null,
    ];
});
