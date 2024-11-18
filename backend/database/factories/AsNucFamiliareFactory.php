<?php

use App\Http\Repositories\Aseguramiento\AfitramiteRepository;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsAfitramite;
use App\Models\Aseguramiento\AsNucfamiliare;
use Faker\Generator as Faker;

$factory->define(AsNucfamiliare::class, function (Faker $faker) {

    $beneficiario = factory(AsAfiliado::class)->make()->toArray();
    return [
        'afiliado' => $beneficiario,
        'as_parentesco_id' => $faker->numberBetween(1,9),
        'upc' => null,
        'rs_entidade_id' => 1,
        'as_afitramite_id' => 85
    ];
});
