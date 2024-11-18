<?php

use App\Models\Aseguramiento\AsArl;
use Faker\Generator as Faker;

$factory->define(AsArl::class, function (Faker $faker) {
    return [
        'codigo' => $faker->numerify('########'),
        'nombre' => $faker->name()
    ];
});
