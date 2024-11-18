<?php

use App\Models\Niif\NfCencosto;
use Faker\Generator as Faker;

$factory->define(NfCencosto::class, function (Faker $faker) {
    return [
        'codigo' => $faker->numerify('#####'),
        'nombre' => $faker->lexify('Centro ???? ???')
    ];
});
