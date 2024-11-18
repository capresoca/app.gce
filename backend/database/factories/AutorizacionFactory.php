<?php

use App\Models\Autorizaciones\AuAutdetalle;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(\App\Models\Autorizaciones\AuAutorizacione::class, function (Faker $faker) {
    $afiliado = \App\Models\Aseguramiento\AsAfiliado::inRandomOrder()->first();
    $entidad = \App\Models\RedServicios\RsEntidade::inRandomOrder()->first();
    $cies = \App\Models\RedServicios\RsCie10::orderBy(DB::raw('RAND()'))->limit(3)->get();
    $modservicio = \App\Models\Autorizaciones\AuModservicio::inRandomOrder()->first();
    $contrato = \App\Models\RedServicios\RsPlanescobertura::whereHas('servicios_contratados')->with('servicios_contratados')->inRandomOrder()->first();

    $archivos = \App\Models\General\GnArchivo::orderBy(DB::raw('RAND()'))->limit(2)->get();

    $tutela = \App\Models\OficinaJuridica\OjTutela::inRandomOrder()->first();

    return [
        'as_afiliado_id' => $afiliado->id,
        'as_regimen_id' => $afiliado->as_regimene_id,
        'entidad_origen_id' => $entidad->id,
        'tipo_origen' => $faker->randomElement(['Ambulatorio','Urgencias','Internación']),
        'cie10_principal_id' => $cies[0]->id,
        'cie10_rel1_id' => $cies[1]->id,
        'cie10_rel2_id' => $cies[2]->id,
        'au_modservicio_id' => $modservicio->id,
        'rs_contrato_id' => $contrato->id,
        'rs_servicio_id' => $contrato->servicios_contratados[0]->id,
        'orden_medica' => $archivos[0]->id,
        'historia_clinica' => $archivos[1]->id,
        'alto_costo' => random_int(0,1),
        'oj_tutela_id' => $tutela->id,
        'pyp' => random_int(0,1),
        'atencion_materna' => random_int(0,1),
        'enfermedad_trasmisible' => random_int(0,1),
        'catastrofe' => random_int(0,1),
        'estado' => 'Registrada',
        'detalles' => factory(AuAutdetalle::class,random_int(1,8))->make()->toArray()
    ];
});




