<?php

use Faker\Generator as Faker;

$factory->define(App\TaskItem::class, function (Faker $faker) {
    return [
        'title' => $faker->name,
        'description' => $faker->text(),
        'questions' => 'Ja;Nein',
        'order' => $faker->numberBetween(0, 15),
    ];
});
