<?php

use Faker\Generator as Faker;
use \App\Helpers\LessonType;

$factory->define(App\Models\Lesson::class, function (Faker $faker) {
    return [
        'start_date' => \Carbon\Carbon::now(),
        'end_date' => \Carbon\Carbon::now()->addHour(),
        'type' => LessonType::LESSON,
        'location' => 'Hofstetterstrasse 7, 4054 Basel',
        'room' => 'R308',
        'canceled' => false,
        'subject_id' => null,
    ];
});

$factory->defineAs(App\Models\Lesson::class, LessonType::EXAM, function (Faker $faker) use ($factory) {
    $lesson = $factory->raw('App\Models\Lesson');

    return array_merge($lesson, ['type' => LessonType::EXAM]);
});

$factory->defineAs(App\Models\Lesson::class, LessonType::REMINDER, function (Faker $faker) use ($factory) {
    $lesson = $factory->raw('App\Models\Lesson');

    return array_merge($lesson, ['type' => LessonType::REMINDER]);
});
