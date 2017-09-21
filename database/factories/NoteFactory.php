<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Note::class, function (Faker $faker) {
    return [
        'note' => $faker->text(),
        'user_id' => 1,
    ];
});
