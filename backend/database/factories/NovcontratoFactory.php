<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\RedServicios\RsNovcontrato::class, function (Faker $faker) {
    return [
        'tipo'          => $faker->randomElement(['Acta Inicio', 'Prorroga']),
        'fecha'         => $faker->date(),
        'valor'         => $faker->randomNumber(7),
        'plazo_meses'   => random_int(1,72),
        'plazo_dias'    => random_int(1,180),
        'descripcion'   => $faker->text(),
    ];
});
