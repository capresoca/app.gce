<?php

use App\Models\ContratacionEstatal\CeEstpregarantia;
use App\Models\ContratacionEstatal\CeGarantia;
use App\Models\ContratacionEstatal\CeProconestprevio;
use Faker\Generator as Faker;

$factory->define(CeEstpregarantia::class, function (Faker $faker) {
    return [
        'ce_garantia_id' => CeGarantia::all()->random()->id
    ];
});
