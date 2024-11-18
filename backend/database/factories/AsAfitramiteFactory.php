<?php

use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsAfitramite;
use App\Models\Aseguramiento\AsPagadore;
use App\Models\Aseguramiento\AsTramite;
use Faker\Generator as Faker;

$factory->define(AsAfitramite::class, function (Faker $faker) {
    $afiliado = factory(AsAfiliado::class)->make()->toArray();
    $afiliado2 = factory(AsAfiliado::class)->make()->toArray();
    $familia = factory(AsAfiliado::class, 3)->make()->toArray();
    $pagador = factory(AsPagadore::class)->make()->toArray();

    return [
        'tramite' => factory(AsTramite::class)->make()->toArray(),
        'as_regimene_id' => $faker->numberBetween(1,2),
        'as_tipafiliacione_id' => $faker->numberBetween(1,4),
        'as_tipafiliado_id' => $faker->numberBetween(1,3),
        'as_tipcotizante_id' => $faker->numberBetween(1,3),
        'as_clasecotizante_id' => $faker->numberBetween(1,20),
        'rs_ips_id' => 1,
        'afiliado' => $afiliado,
        'conyuge' => $afiliado2,
        'pagador' => $pagador,
    ];
});
