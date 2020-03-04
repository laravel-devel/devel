<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Modules\DevelCore\Entities\Auth\Role;

$factory->define(Role::class, function (Faker $faker) {
    $name = $faker->uuid;

    return [
        'key' => strtolower($name),
        'name' => $name,
    ];
});