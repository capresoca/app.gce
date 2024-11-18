<?php

use App\Models\Aseguramiento\AsPagadore;
use App\Models\Niif\GnTercero;
use Faker\Generator as Faker;

$factory->define(AsPagadore::class, function (Faker $faker) {
    $tercero = factory(GnTercero::class)->make()->toArray();
    return [
        'tercero' => $tercero,
        'as_tipaportante_id' => $faker->numberBetween(1,5)
    ];
});
