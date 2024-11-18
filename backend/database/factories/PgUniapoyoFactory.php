<?php

use App\Models\Niif\NfCencosto;
use App\Models\Pagos\PgUniapoyo;
use Faker\Generator as Faker;

$factory->define(PgUniapoyo::class, function (Faker $faker) {
    return [
        'codigo' => $faker->numerify('#####'),
        'nombre' => $faker->name(),
        'nf_cencosto_id' => NfCencosto::all()->random()->id
    ];
});
