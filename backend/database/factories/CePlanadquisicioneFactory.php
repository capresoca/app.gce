<?php

use App\Models\ContratacionEstatal\CePlanadquisicione;
use Faker\Generator as Faker;

$factory->define(CePlanadquisicione::class, function (Faker $faker) {
    $planadquisicione = [
        'anio' => $faker->year(),
        'descripcion' => $faker->text(),
        'estado' => $faker->randomElement(['Registrado', 'Activo', 'Inactivo']),
        'gs_usuario_id' => 1
    ];
    return $planadquisicione;
});
