<?php

use App\Models\Aseguramiento\AsCcf;
use Faker\Generator as Faker;

$factory->define(AsCcf::class, function (Faker $faker) {
    return [
        'cod_habilitacion' => $faker->numerify('######'),
        'nombre' => $faker->name(),
        'estado' => 'Activo'

    ];
});
