<?php

use App\Models\Niif\NfNivcuenta;
use Faker\Generator as Faker;

$factory->define(NfNivcuenta::class, function (Faker $faker) {
    return [
        'codigo' => $faker->numerify('#####'),
        'nombre' => $faker->lexify('Nivel. ???? ???')
    ];
});
