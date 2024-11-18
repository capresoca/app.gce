<?php

use App\Models\ContratacionEstatal\CeNatjuridica;
use Faker\Generator as Faker;

$factory->define(CeNatjuridica::class, function (Faker $faker) {
    return [
        'codigo' => $faker->numerify('#####'),
        'nombre' => $faker->text(),
        'estado' => $faker->randomElement(['Activo', 'Inactivo'])
    ];
});
