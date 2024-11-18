<?php

use App\Models\Aseguramiento\AsAfp;
use Faker\Generator as Faker;

$factory->define(AsAfp::class, function (Faker $faker) {
    return [
        'codigo' => $faker->numerify('########'),
        'nombre' => $faker->name
    ];
});
