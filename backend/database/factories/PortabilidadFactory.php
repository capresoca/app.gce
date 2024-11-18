<?php

use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;
$factory->define(\App\Models\RedServicios\RsPortabilidade::class, function (Faker $faker) {
    $fecha_solicitud = $faker->dateTimeThisMonth('now')->format('Y-m-d');
    $fecha_inicio = \Carbon\Carbon::createFromFormat('Y-m-d',$fecha_solicitud)->addDays(random_int(1,45));
    $fecha_fin = $fecha_inicio->addDays(random_int(1,60));
    $fecha_tramite = \Carbon\Carbon::createFromFormat('Y-m-d',$fecha_solicitud)->addDays(random_int(1,5));
    return [
        'as_afiliado_id' => \App\Models\Aseguramiento\AsAfiliado::orderBy(DB::raw('RAND()'))->limit(1)->first()->id,
        'fecha_solicitud' => $fecha_solicitud,
        'fecha_inicio' => $fecha_inicio->toDateString(),
        'fecha_fin' => $fecha_fin->toDateString(),
        'munreceptor_id' => \App\Models\General\GnMunicipio::orderBy(DB::raw('RAND()'))->limit(1)->first()->id,
        'entidad_id' => \App\Models\RedServicios\RsEntidade::orderBy(DB::raw('RAND()'))->limit(1)->first()->id,
        'direccion' => $faker->address,
        'telefono' => $faker->phoneNumber,
        'gn_archivo_id' => \App\Models\General\GnArchivo::orderBy(DB::raw('RAND()'))->limit(1)->first()->id,
        'estado' => 'Registrado',
        'gs_usuario_id' => 1,
        'usuario_tramita_id' => \App\User::orderBy(DB::raw('RAND()'))->limit(1)->first()->id,
        'fecha_tramite' => $fecha_tramite->toDateString(),
        'observaciones' => $faker->text

    ];
});
