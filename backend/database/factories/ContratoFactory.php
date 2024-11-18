<?php

use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(\App\Models\RedServicios\RsPlanescobertura::class, function (Faker $faker) {

    return [
        'fecha_contrato' => $faker->dateTimeThisMonth()->format('Y-m-d'),
        'numero_contrato' => $faker->numerify('######'),
        'rs_entidad_id' => \App\Models\RedServicios\RsEntidade::orderBy(DB::raw('RAND()'))->limit(1)->first()->id,
        'objeto' => $faker->text,
        'tipo' => $faker->randomElement(['Capitado','Evento']),
        'plazo_meses' => $faker->numberBetween(1,12),
        'plazo_dias' => $faker->numberBetween(1,365),
        'valor' => $faker->numberBetween(200000,200000000),
        'estado' => $faker->randomElement(['Registrado','Legalizado','Terminado']),
        'gs_usuario_id' => \App\User::orderBy(DB::raw('RAND()'))->limit(1)->first()->id
    ];
});


