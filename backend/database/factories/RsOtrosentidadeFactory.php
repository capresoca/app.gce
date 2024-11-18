<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\RedServicios\RsOtrosentidade::class, function (Faker $faker) {
    $entidades = \App\Models\RedServicios\RsEntidade::select('id')->limit(100)->get()->pluck('id');

    return [
        'rs_entidades_id' => $faker->randomElement($entidades),
        'codigo' => $faker->numerify('######'),
        'nombre' => $faker->sentence(),
        'estado' => $faker->randomElement(['Activo','Inactivo']),
        'tarifa' => $faker->randomNumber()
    ];
});
