<?php

use App\Models\Pagos\PgConpago;
use App\Models\Pagos\PgCxp;
use App\Models\Pagos\PgCxpdetalle;
use App\Models\Pagos\PgUniapoyo;
use Faker\Generator as Faker;

$factory->define(PgCxpdetalle::class, function (Faker $faker) {
    return [
//        'pg_cxp_id' => PgCxp::all()->random(),
        'pg_conpago_id' => PgConpago::all()->random()->id,
        'pg_uniapoyo_id' => PgUniapoyo::all()->random()->id,
        'valor' => $faker->randomNumber('2')
    ];
});
