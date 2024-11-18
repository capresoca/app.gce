<?php

use App\Models\Niif\NfTipcomdiario;
use Faker\Generator as Faker;

$factory->define(NfTipcomdiario::class, function (Faker $faker) {
    return [
        'codigo' => $faker->numerify('#####'),
        'nombre' => $faker->lexify('Tipo de comp. ???? ???')
    ];
});


