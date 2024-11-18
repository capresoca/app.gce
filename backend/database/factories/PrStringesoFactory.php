<?php

use App\Models\Presupuesto\PrEntidadResolucione;
use App\Models\Presupuesto\PrStringreso;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Auth;

$factory->define(PrStringreso::class, function (Faker $faker) {
    return [
        'periodo' => '2019',
        'pr_entidad_resolucion_id' => PrEntidadResolucione::all()->random()->id,
        'gs_usuario_id' => 1,
        'gs_usuario_conf_id' => 3,
        'estado' => 'Confirmada',
        'fecha' => Carbon::now()->toDateTimeString(),
        'fecha_confirmacion' => Carbon::now()->toDateTimeString()
    ];
});
