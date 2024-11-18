<?php

use App\Models\Niif\GnTercero;
use App\Models\Tesoreria\TsBanco;
use Faker\Generator as Faker;

$factory->define(TsBanco::class, function (Faker $faker) {
    return [
        'codigo' => $faker->numerify('#####'),
        'nombre' => $faker->company(),
        'gn_tercero_id' => GnTercero::where('gn_tipdocidentidad_id',12)->limit(100)->get()->random()->id,
        'codigo_ach' => $faker->numerify('#######')
    ];
});
