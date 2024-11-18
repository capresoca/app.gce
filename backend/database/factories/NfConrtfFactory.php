<?php

use App\Models\Niif\NfConrtf;
use Faker\Generator as Faker;

$factory->define(NfConrtf::class, function (Faker $faker) {
    return [
        'codigo' => $faker->numerify('#####'),
        'nombre' => $faker->lexify('Concepto ????'),
        'manejo' => $faker->randomElement(['Base','Rangos','Variable']),
        'base_minima' => $faker->randomElement([10000,500000,5000,80000]),
        'porcentaje' => $faker->randomNumber(2),
        'estado' => 'Activo'
    ];
});
