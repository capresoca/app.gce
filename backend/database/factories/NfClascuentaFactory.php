<?php

use App\Models\Niif\NfClascuenta;
use Faker\Generator as Faker;

$factory->define(NfClascuenta::class, function (Faker $faker) {
    return [
        'codigo' => $faker->numerify('#####'),
        'nombre' => $faker->lexify('Centro ???? ???'),
        'tipo' => $faker->randomElement(['Presupuesto','Balance','Resultado','Orden']),
        'naturaleza' => $faker->randomElement(['Débito','Crédito']),
        'patrimonio' => 0
    ];
});
