<?php

use App\Models\Niif\NfNiif;
use App\Models\Pagos\PgConpago;
use Faker\Generator as Faker;

$factory->define(PgConpago::class, function (Faker $faker) {
    return [
        'codigo' => $faker->numerify('#####'),
        'nombre' => $faker->name(),
        'tipo_concepto' => $faker->randomElement(['CXP','Notas','Traslado de Valores','Saldos Iniciales']),
        'nf_niif_id' => NfNiif::where('nf_nivcuenta_id',5)->get()->random()->id
    ];
});
