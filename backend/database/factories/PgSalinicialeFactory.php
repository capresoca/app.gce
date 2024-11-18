<?php

use App\Models\Niif\NfNiif;
use App\Models\Pagos\PgProveedore;
use App\Models\Pagos\PgSaliniciale;
use Faker\Generator as Faker;

$factory->define(PgSaliniciale::class, function (Faker $faker) {
    $saliniciale = [
        'fecha' => $faker->date(),
        'tipo' => $faker->randomElement(['CXP','Anticipo']),
        'pg_proveedore_id' => PgProveedore::all()->random()->id,
        'nf_niif_id' => NfNiif::where('nf_nivcuenta_id',5)->get()->random()->id,
        'valor' => $faker->numerify('#####'),
        'no_documento' => $faker->numerify('############'),
        'fecha_documento' => $faker->date(),
        'fecha_vencimiento' => $faker->date(),
        'observaciones' => $faker->text(),
        'estado' => $faker->randomElement(['Registrado','Confirmado', 'Anulado'])
    ];

    if ($saliniciale['estado'] === 'Anulado') {
        $saliniciale['detalle_anulacion'] = $faker->text();
    }

    return $saliniciale;
});
