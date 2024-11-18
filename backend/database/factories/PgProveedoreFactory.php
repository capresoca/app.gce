<?php

use App\Models\Niif\GnTercero;
use App\Models\Niif\NfNiif;
use App\Models\Pagos\PgProveedore;
use Faker\Generator as Faker;

$factory->define(PgProveedore::class, function (Faker $faker) {
    return [
        'gn_tercero_id' => GnTercero::all()->random()->id,
        'nf_niif_id' => NfNiif::where('nf_nivcuenta_id',5)->get()->random()->id,
        'tipo_proveedor' => $faker->randomElement(['Inventario', 'Activos Fijos', 'Servicios']),
        'plazo' => $faker->randomNumber(2)
    ];
});
