<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Compensacion\CpArchivosaporte::class, function (Faker $faker) {
    return [
        'archivo_financiero_id' => \App\Models\General\GnArchivo::max('id')->id,
        'archivo_pila' => \App\Models\General\GnArchivo::max('id')->id,
        'estado' => 'Procesado'
    ];
});
