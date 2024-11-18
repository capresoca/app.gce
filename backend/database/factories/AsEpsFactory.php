<?php

use App\Models\Aseguramiento\AsEps;
use Faker\Generator as Faker;

$factory->define(AsEps::class, function (Faker $faker) {
    return [
        'nit' => $faker->numerify('##########'),
        'cod_habilitacion' => $faker->numerify('#########'),
        'nombre' => $faker->text(),
        'regimen' => $faker->randomElement(['Subsidiado', 'Contributivo', 'Especial', 'PVS']),
        'estado' => $faker->randomElement(['Activa', 'Inactiva'])
    ];
});
