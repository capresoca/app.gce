<?php

use App\Models\General\GnMunicipio;
use App\Models\OficinaJuridica\OjJuzgado;
use Faker\Generator as Faker;

$factory->define(OjJuzgado::class, function (Faker $faker) {
    return [
        'codigo' => $faker->numerify('########'),
        'nombre' => $faker->name(),
        'gn_municipio_id' => GnMunicipio::all()->random()->id,
        'direccion' => $faker->text(),
        'telefono' => $faker->phoneNumber(),
        'email' => $faker->email()
    ];
});
