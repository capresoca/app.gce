<?php

use App\Models\Aseguramiento\AsFormpagadore;
use App\Models\Aseguramiento\AsTipaportante;
use App\Models\Niif\GnTercero;
use Faker\Generator as Faker;

$factory->define(AsFormpagadore::class, function (Faker $faker) {
    $formpagador = [
        'gn_tercero_id' => GnTercero::limit(100)->get()->random()->id,
        'as_tipaportante_id' => AsTipaportante::all()->random()->id,
        'sector_aportante' => $faker->randomElement(['Público', 'Privado', 'Mixto']),
        'estado' => 'Radicado'
//        'estado' => $faker->randomElement(['Registrado','Radicado','Anulado'])
    ];

    $tercero = GnTercero::find($formpagador['gn_tercero_id']);

    if ($tercero->gn_tipdocidentidad_id === 12) {
        $formpagador['digito_verificacion'] = $faker->numerify('#');
    }
    if ($formpagador['estado'] === 'Radicado') {
        $formpagador['fecha_radicacion'] = $faker->date();
    } elseif ($formpagador['estado'] === 'Anulado') {
         $formpagador['detalle_anulacion'] = $faker->text();
    }

    return $formpagador;

});
