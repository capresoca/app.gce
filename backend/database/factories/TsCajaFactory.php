<?php

use App\Models\Niif\NfCencosto;
use App\Models\Niif\NfNiif;
use Faker\Generator as Faker;

$factory->define(\App\Models\Tesoreria\TsCaja::class, function (Faker $faker) {
    return [
        'codigo' => $faker->numerify('#####'),
        'nombre' => $faker->text(80),
        'tipo' => $faker->randomElement(['Mayor', 'Menor']),
        'fecha_apertura' => $faker->date(),
        'saldo_inicial' => $faker->randomElement([40000,80000,100000,1000000,60000,120000,45000]),
        'nf_niif_id' => NfNiif::all()->random()->id,
        'nf_cencosto_id' => NfCencosto::all()->random()->id
    ];
});
