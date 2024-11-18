<?php

use App\Models\ContratacionEstatal\CeFambienservicio;
use App\Models\ContratacionEstatal\CeSegbienservicio;
use Faker\Generator as Faker;

$factory->define(CeFambienservicio::class, function (Faker $faker) {
    return [
        'ce_segbienservicio_id' => CeSegbienservicio::all()->random()->id,
        'codigo' => $faker->numerify('####'),
        'nombre' => $faker->name(),
        'estado' => $faker->randomElement(['Activo', 'Inactivo'])
    ];
});
