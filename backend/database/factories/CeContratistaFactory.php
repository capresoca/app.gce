<?php

use App\Models\ContratacionEstatal\CeContratista;
use App\Models\ContratacionEstatal\CeNatjuridica;
use App\Models\General\GnMunicipio;
use App\Models\Niif\GnTercero;
use Faker\Generator as Faker;

$factory->define(CeContratista::class, function (Faker $faker) {
    return [
        'ccomercio' => $faker->name(),
        'fecha_ccomercio' => $faker->date(),
        'rep_legal' => $faker->text(),
        'gn_tercero_id' =>  GnTercero::limit(100)->get()->random()->id,
        'gn_munccomercio_id' =>  GnMunicipio::all()->random()->id,
        'ce_natjuridica_id' => CeNatjuridica::all()->random()->id
    ];
});
