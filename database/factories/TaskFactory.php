<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Task::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->text(),
        'due_date' => \Carbon\Carbon::now()->addDays(3),
        'lesson_id' => null,
    ];
});
