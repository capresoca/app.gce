<?php

use App\Models\ContratacionEstatal\CeDetplanadquisicione;
use App\Models\ContratacionEstatal\CeProcontractuale;
use App\Models\Pagos\PgUniapoyo;
use App\Models\Presupuesto\PrCdp;
use App\Models\Presupuesto\PrRubro;
use Faker\Generator as Faker;

$factory->define(CeProcontractuale::class, function (Faker $faker) {
    return [
        'nombre' => $faker->name(),
        'objeto' => $faker->text(),
        'estado' => $faker->randomElement(['Registrado', 'Estudio Previo', 'Convocado', 'Adjudicado', 'Descartado', 'Terminado Anormalmente', 'Celebrado', 'Liquidado', 'Terminado']),
        'pg_uniapoyo_id' => PgUniapoyo::all()->random()->id,
        'ce_detplanadquisicione_id' => CeDetplanadquisicione::all()->random()->id,
        'gs_usuario_id' => 1,
        'pr_cdp_id' => PrCdp::all()->random()->id,
        'pr_rubro_id' => PrRubro::all()->random()->id,
        'servicios_salud' => $faker->numerify('##')
    ];
});
