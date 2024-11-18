<?php

use App\Models\ContratacionEstatal\CeEstpreforpago;
use App\Models\ContratacionEstatal\CeProconestprevio;
use Faker\Generator as Faker;

$factory->define(CeEstpreforpago::class, function (Faker $faker) {
    return [
        'tipo' => $faker->randomElement(['Porcentaje', 'Valor']),
        'valor' => $faker->numerify('###'),
        'fecha' => $faker->date()
    ];
});
