<?php

use App\Models\ContratacionEstatal\CeBienservicio;
use App\Models\ContratacionEstatal\CeDetplanadquisicione;
use App\Models\ContratacionEstatal\CeModcontratacione;
use App\Models\ContratacionEstatal\CePlanadquisicione;
use App\Models\Pagos\PgUniapoyo;
use App\Models\Presupuesto\PrRubro;
use Faker\Generator as Faker;

$factory->define(CeDetplanadquisicione::class, function (Faker $faker) {
    return [
        'ce_planadquisicione_id' => CePlanadquisicione::all()->random()->id,
        'ce_bienservicio_id' => CeBienservicio::limit(100)->get()->random()->id,
        'fecha_proceso' => $faker->date(),
        'fecha_ofertas' => $faker->date(),
        'duracion' => $faker->numerify('###'),
        'pg_uniapoyo_id' => PgUniapoyo::all()->random()->id,
        'ce_modcontratacione_id' => CeModcontratacione::all()->random()->id,
        'valor' => $faker->numerify('######'),
        'pr_rubro_id' => PrRubro::all()->random()->id
    ];
});
