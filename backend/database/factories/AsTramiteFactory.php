<?php

use App\Models\Aseguramiento\AsTramite;
use Faker\Generator as Faker;

$factory->define(\App\Models\Aseguramiento\AsTramite::class, function (Faker $faker) {
    return [
        'tipo_tramite' => $faker->randomElement(['Afiliacion','Reporte de Novedad','Novedad Interna']),
        'consecutivo' => AsTramite::all()->pluck('consecutivo')->last() + 1,
        'clase_tramite' => $faker->randomElement(['Automatico','Manual']),
        'fecha'=> $faker->date(),
        'gs_usuradica_id' => 1,
        'estado' => 'Radicado',
        'fecha_radicacion' => '2018-08-03',
        'gs_usuario_id' => 1
    ];
});
