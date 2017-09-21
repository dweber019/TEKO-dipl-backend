<?php

use Faker\Generator as Faker;

$factory->define(App\Models\TaskItem::class, function (Faker $faker) {
    return [
        'title' => $faker->name,
        'description' => $faker->text(),
        'question_type' => $faker->randomElement(['toggle', 'select', 'file', 'input', 'text']),
        'question' => 'Ja;Nein',
        'order' => $faker->numberBetween(0, 15),
    ];
});
