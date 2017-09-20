<?php

use Faker\Generator as Faker;

$factory->define(App\Subject::class, function (Faker $faker) {
    return [
      'name' => $faker->name,
      'archived' => $faker->boolean(),
    ];
});
