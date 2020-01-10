<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Modules\DevelCore\Entities\Auth\User;

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$3ptqP6dstrDsYd.Ptmo/Duc2W09b72dUdkZQ.LflEvYbEv/1FHVlO', // qwerty
        'remember_token' => Str::random(10),
    ];
});
