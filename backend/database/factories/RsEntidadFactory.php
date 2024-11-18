<?php

use App\Models\Niif\GnTercero;
use App\Models\RedServicios\RsEntidade;
use Faker\Generator as Faker;

$factory->define(RsEntidade::class, function (Faker $faker) {

    return [
        'gn_tercero_id' => random_int(1,2000),
        'gerente' => $faker->name,
        'replegal' => $faker->name,
        'cod_habilitacion' => $faker->numerify('######'),
        'rs_tipentidade_id' => \App\Models\RedServicios\RsTipentidade::all()->random()->id,
        'res_habilitacion' => $faker->numerify('#######'),
        'nombre' => $faker->company,
        'correo_electronico_sede' => $faker->companyEmail,
        'telefono_sede' => $faker->phoneNumber,
        'direccion_sede' => $faker->address,
        'gn_municipiosede_id' => \App\Models\General\GnMunicipio::all()->random()->id,
        'tipo_locacion' => $faker->randomElement(['Principal','Sede']),
        'naturaleza' => $faker->randomElement(['Publica','Privada','Mixta']),
        'complejidad' => $faker->randomElement(['Nivel 1','Nivel 2','Nivel 3','Nivel 4'])
    ];


});
