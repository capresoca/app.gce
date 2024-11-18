<?php

use App\Models\ContratacionEstatal\CeBienservicio;
use App\Models\ContratacionEstatal\CeClasbienservicio;
use Faker\Generator as Faker;

$factory->define(CeBienservicio::class, function (Faker $faker) {
    return [
        'ce_clasbienservicio_id' => CeClasbienservicio::all()->random()->id,
        'codigo' => $faker->numerify('########'),
        'nombre' => $faker->name(),
        'estado' => $faker->randomElement(['Activo', 'Inactivo']),
        'gs_usuario_id' => 1
    ];
});
