<?php

use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(\App\Models\AtencionUsuario\AuIncapacidade::class, function (Faker $faker) {
    $afiliado = \App\Models\Aseguramiento\AsAfiliado::whereHas('relaciones_laborales')
        ->orderBy(DB::raw('RAND()'))->limit(1)->first();

    $tipo = \App\Models\AtencionUsuario\AuTipincapacidade::all()->random();

    $fecha_inicio = $faker->dateTimeThisMonth()->format('Y-m-d');

    $fecha_fin = \Carbon\Carbon::createFromFormat('Y-m-d',$fecha_inicio)->addDays(random_int(1,$tipo->max_dias));

    $archivos = \App\Models\General\GnArchivo::orderBy(DB::raw('RAND()'))->limit(3)->get();


    return [
        'as_afiliados_id' => $afiliado->id,
        'as_afiliado_pagador_id' => $afiliado->relaciones_laborales[0],
        'au_tipincapacidades_id' => $tipo,
        'fecha_inicio' => $fecha_inicio,
        'fecha_fin' => $fecha_fin,
        'base_liquidacion' => $faker->randomNumber(7),
        'pagar_a' => $faker->randomElement(['Afiliado','Aportante']),
        'fecha' => $faker->dateTimeThisMonth(),
        'incapacidad_id' => $archivos[0],
        'histclinica_id' => $archivos[1],
        'certbanco_id' => $archivos[2],
        'gs_usuarios_id' => 1,
        'estado' => $faker->randomElement(['Liquidada','Aprobada','Negada','Pagada']),
        'usutramita_id' => 1,
        'fecha_tramite' => $fecha_inicio,
        'observaciones' => $faker->text,
        'semanas_gestacion' => $tipo->id === 1 ? random_int(1,126) : null,
        'parto_multiple' => 0
    ];
});
