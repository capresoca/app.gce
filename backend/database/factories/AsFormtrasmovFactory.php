<?php

use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsEps;
use App\Models\Aseguramiento\AsFormtrasmov;
use App\Models\Aseguramiento\AsPagadore;
use App\Models\Aseguramiento\AsParentesco;
use App\Models\General\GnMunicipio;
use App\Models\General\GnSexo;
use App\Models\General\GnTipdocidentidade;
use Faker\Generator as Faker;

$factory->define(AsFormtrasmov::class, function (Faker $faker) {
    return [
        'tipo' => $faker->randomElement(['Movilidad','Traslado']),
        'as_afiliado_id' => AsAfiliado::limit(100)->get()->random()->id,
        'as_padre_id' =>  AsAfiliado::limit(100)->get()->random()->id,
        'as_parentesco_id' => AsParentesco::all()->random()->id,
        'as_pagadore_id' => AsPagadore::all()->random()->id,
        'as_eps_id' => AsEps::all()->random()->id,
        'gn_tipdocidentidade_id' => GnTipdocidentidade::all()->random()->id,
        'identificacion' => $faker->numerify('#########'),
        'nombre1' => $faker->name(),
        'nombre2'=> $faker->name(),
        'apellido1'=> $faker->lastName(),
        'apellido2'=> $faker->lastName(),
        'gn_sexo_id' => GnSexo::all()->random()->id,
        'gn_municipioexpedicion_id' => GnMunicipio::all()->random()->id,
        'fecha_nacimiento' => $faker->date(),
        'fecha_traslado' => $faker->date(),
        'estado' => 'Registrado',
        'gs_usucrea_id' => 1,
        'gs_usuconf_id' => 1,
        'gs_usuanu_id' => 1,
        'motivo_anula' => $faker->text()
    ];
});
