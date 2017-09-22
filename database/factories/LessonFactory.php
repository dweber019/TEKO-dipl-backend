<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Lesson::class, function (Faker $faker) {
    return [
        'start_date' => \Carbon\Carbon::now(),
        'end_date' => \Carbon\Carbon::now()->addHour(),
        'type' => 'lesson',
        'location' => 'Hofstetterstrasse 7, 4054 Basel',
        'room' => 'R308',
        'canceled' => false,
        'subject_id' => null,
    ];
});

$factory->defineAs(App\Models\Lesson::class, 'exam', function (Faker $faker) use ($factory) {
    $lesson = $factory->raw('App\Models\Lesson');

    return array_merge($lesson, ['type' => 'exam']);
});

$factory->defineAs(App\Models\Lesson::class, 'reminder', function (Faker $faker) use ($factory) {
    $lesson = $factory->raw('App\Models\Lesson');

    return array_merge($lesson, ['type' => 'reminder']);
});
