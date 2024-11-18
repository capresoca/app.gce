<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Mipres\MpNotificacion::class, function (Faker $faker) {
    return [
        'mp_direccionamiento_id'    => \App\Models\Mipres\MpDireccionamiento::all()->random()->id,
        'tipo'                      => 'telefónica',
        'notificacion_exitosa'      => 1,
        'aceptada'                  => 1,
        'observaciones'             => $faker->realText()
    ];
});
