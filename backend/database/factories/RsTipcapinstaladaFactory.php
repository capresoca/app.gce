<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\RerServicios\RsTipcapinstalada::class, function (Faker $faker) {
    return [
        'tipo' => $faker->word,
        'descripcion' => $faker->sentence
    ];
});
