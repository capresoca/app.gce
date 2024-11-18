<?php

use App\Models\Tesoreria\TsConcepto;
use Faker\Generator as Faker;

$factory->define(TsConcepto::class, function (Faker $faker) {
    return [
        'codigo' => $faker->numerify('#####'),
        'nombre' => $faker->lexify('Centro ???? ???'),
        'tipo' => $faker->randomElement(['Recibos de Caja','Comprobantes de Egreso','Notas','Consignacion','Traslado Bancario']),
        'nf_niif_id' => \App\Models\Niif\NfNiif::all()->random()->id
    ];
});
