<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Lesson::class, function (Faker $faker) {
    return [
        'start_date' => \Carbon\Carbon::now(),
        'end_date' => \Carbon\Carbon::now()->addHour(),
        'location' => 'Hofstetterstrasse 7, 4054 Basel',
        'room' => 'R308',
        'canceled' => false,
    ];
});
