<?php

use Faker\Generator as Faker;
use \App\Helpers\UserTypes;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Models\User::class, function (Faker $faker) {
    return [
        'firstname' => $faker->firstName,
        'lastname' => $faker->lastName,
        'invite_token' => 'ABC',
        'invite_email' => $faker->unique()->safeEmail,
        'type' => UserTypes::STUDENT
    ];
});

$factory->defineAs(App\Models\User::class, UserTypes::TEACHER, function (Faker $faker) use ($factory) {
    $user = $factory->raw('App\Models\User');

    return array_merge($user, ['type' => UserTypes::TEACHER]);
});

$factory->defineAs(App\Models\User::class, UserTypes::ADMIN, function (Faker $faker) use ($factory) {
    $user = $factory->raw('App\Models\User');

    return array_merge($user, ['type' => UserTypes::ADMIN]);
});
