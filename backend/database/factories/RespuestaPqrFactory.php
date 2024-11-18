<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\AtencionUsuario\AuRespuestapqrsd::class, function (Faker $faker) {
    return [
        'respuesta' => $faker->text,
        'tipo' => $faker->randomElement(['Positiva','Negativa','Otra']),
        'fecha_respuesta' => $faker->dateTime()
    ];
});
