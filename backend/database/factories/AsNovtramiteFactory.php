<?php

use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsNovtramite;
use App\Models\Aseguramiento\AsTipnovedade;
use App\Models\Aseguramiento\AsTramite;
use App\Models\General\GnEmpresa;
use Faker\Generator as Faker;

$factory->define(AsNovtramite::class, function (Faker $faker) {

    $afiliado = AsAfiliado::find($faker->numberBetween(577000,578000));
    $empresa = GnEmpresa::first();
    $novtramite = [
        'as_tramite_id' => AsTramite::all()->pluck('consecutivo')->last() + 1,
        'as_afiliado_id' => $afiliado->id,
        'gn_municipio_id' => $afiliado->gn_municipio_id,
        'codigo_entidad' => $afiliado->regimene_id === 1 ? $empresa->codhabilitacion_subsid : $empresa->codhabilitacion_contrib,
        'gn_tipdocidentidad_id' => $afiliado->gn_tipdocidentidad_id,

    ];


    return $novtramite;

});
