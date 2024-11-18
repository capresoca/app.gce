<?php

use App\Models\ContratacionEstatal\CeEstpreactividade;
use App\Models\ContratacionEstatal\CeProconestprevio;
use Faker\Generator as Faker;

$factory->define(CeEstpreactividade::class, function (Faker $faker) {
    return [
        'actividad' => $faker->text()
    ];
});
