<?php

use App\Models\Niif\NfNiif;
use App\Models\Pagos\PgCxp;
use App\Models\Pagos\PgProveedore;
use Faker\Generator as Faker;

$factory->define(PgCxp::class, function (Faker $faker) {
    return [
        'fecha_causacion' => $faker->date(),
        'pg_proveedore_id' => PgProveedore::all()->random()->id,
        'no_factura' => $faker->numerify('####################'),
        'fecha_factura'=> $faker->date(),
        'nf_niif_id' => NfNiif::where('nf_nivcuenta_id',5)->get()->random()->id,
        'observaciones' => $faker->text(),
        'modulo' => $faker->randomElement(['CXP','Inventarios'])
    ];
});
