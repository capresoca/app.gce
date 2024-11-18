<?php

use App\Models\ContratacionEstatal\CeSegbienservicio;
use Faker\Generator as Faker;

$factory->define(CeSegbienservicio::class, function (Faker $faker) {
    return [
        'codigo' => $faker->numerify('#####'),
        'nombre' => $faker->name(),
        'estado' => $faker->randomElement(['Activo', 'Inactivo'])
    ];
});
