<?php

use App\Models\Tesoreria\TsCuenta;
use Faker\Generator as Faker;

$factory->define(TsCuenta::class, function (Faker $faker) {
    return [
        'ts_banco_id' => \App\Models\Tesoreria\TsBanco::all()->random()->id,
        'codigo' => $faker->numerify('#####'),
        'tipo_cuenta' => $faker->randomElement(['Ahorros','Corriente']),
        'numero_cuenta' => $faker->numerify('#########'),
        'fecha_apertura' => $faker->date(),
        'saldo_inicial' => $faker->numerify('########'),
        'control_auto' => $faker->randomElement(['Si','No']),
        'nf_niif_id' => 1202,
        'nf_cencosto_id' => \App\Models\Niif\NfCencosto::all()->random()->id,
        'gn_municipio_id' => \App\Models\General\GnMunicipio::all()->random()->id,
        'estado' => 'Activo'
    ];
});


