<?php

use App\Models\ContratacionEstatal\CeEstpreactividade;
use App\Models\ContratacionEstatal\CeEstpreforpago;
use App\Models\ContratacionEstatal\CeEstpregarantia;
use App\Models\ContratacionEstatal\CeProconestprevio;
use App\Models\ContratacionEstatal\CeProcontractuale;
use App\Models\ContratacionEstatal\CeTipcontratacione;
use App\Models\General\GnMunicipio;
use App\Models\Presupuesto\PrRubro;
use Faker\Generator as Faker;

$factory->define(CeProconestprevio::class, function (Faker $faker) {
    $ce_proconestprevio = [
        'ce_procontractuale_id' => CeProcontractuale::all()->random()->id,
        'fecha' => $faker->date(),
        'pr_rubro_id' => PrRubro::all()->random()->id,
        'desc_necesidad' => $faker->text(),
        'desc_tecnica' => $faker->text(),
        'esp_tecnicas' => $faker->text(),
        'sop_economico' => $faker->text(),
        'alt_ejecucion' => $faker->text(),
        'pos_riesgos' => $faker->text(),
        'ce_tipcontratacione_id' => CeTipcontratacione::all()->random()->id,
        'obj_contratar' => $faker->text(),
        'plazo' => $faker->numerify('###'),
        'gn_lugejecucion_id' => GnMunicipio::all()->random()->id,
        'valor' => $faker->numerify('####'),
        'supervicion' => $faker->text(),
        'tipo' => $faker->randomElement(['Servicios de Salud', 'Otro']),
//        'actividades' => factory(CeEstpreactividade::class, 3)->make()->toArray(),
//        'garantias' => factory(CeEstpregarantia::class, 3)->make()->toArray(),
//        'forPagos' => factory(CeEstpreforpago::class, 3)->make()->toArray()
    ];

    return $ce_proconestprevio;
});
