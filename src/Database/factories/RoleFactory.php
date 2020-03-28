<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Devel\Models\Auth\Role;

$factory->define(Role::class, function (Faker $faker) {
    $name = $faker->uuid;

    return [
        'key' => strtolower($name),
        'name' => $name,
    ];
});
