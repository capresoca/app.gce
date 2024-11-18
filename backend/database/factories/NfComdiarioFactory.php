<?php

use App\Models\Niif\NfComdiario;
use App\Models\Niif\NfTipcomdiario;
use Faker\Generator as Faker;

$factory->define(NfComdiario::class, function (Faker $faker) {

    return [
        'nf_tipcomdiario_id' => NfTipcomdiario::all()->random()->id,
        'fecha' => $faker->date('Y-m-d'),
        'detalle' => $faker->text(),
        'estado' => 'Registrado'

    ];
});
