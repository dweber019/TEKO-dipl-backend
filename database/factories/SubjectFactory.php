<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Subject::class, function (Faker $faker) {
    return [
      'name' => $faker->name,
      'archived' => false,
      'teacher_id' => null,
    ];
});
