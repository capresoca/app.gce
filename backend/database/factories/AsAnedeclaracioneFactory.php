<?php

use App\Models\Niif\NfAnedeclaracione;
use Faker\Generator as Faker;

$factory->define(NfAnedeclaracione::class, function (Faker $faker) {
    return [
        'codigo' => $faker->numerify('#####'),
        'nombre' => $faker->lexify('Anexo ????'),
        'estado' => 'Activo'
    ];
});
