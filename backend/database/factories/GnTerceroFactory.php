<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Niif\GnTercero::class, function (Faker $faker) {
    return [
        'gn_tipdocidentidad_id' => 1,
        'identificacion' => $faker->numberBetween($min = 10000000, $max = 900000000),
        'gn_munexpedicion_id' => $faker->numberBetween($min = 1, $max = 1000),
        'nombre1' => $faker->firstName(),
        'nombre2' => $faker->firstName(),
        'apellido1' => $faker->lastName(),
        'apellido2' => $faker->lastName(),
        'direccion' => $faker->address(),
        'correo_electronico' => $faker->email(),
        'gn_municipio_id' => '1',
        'tipo_retencion' => 'Hacer Retencion',
        'tipo_contribuyente' => 'No Responsables de IVA',
        'tipo_persona' => 'Natural',
        'ica' => 1,
        'porcentaje_ica' => 2.4,
        'razon_social' => $faker->company(),
        'tipo_tercero' => ['Afiliado'],
        'autorretenedor' => ['Autorretenedor IVA'],
        'nf_ciiu_id' => 1640,
        'celular' => $faker->phoneNumber(),
        'telefono_fijo' => $faker->phoneNumber()
    ];
});
