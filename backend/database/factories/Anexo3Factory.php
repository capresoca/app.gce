<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Autorizaciones\AuAnexot3::class, function (Faker $faker) {
    return[
        'nSolicitud'    => null,
        'fOrdMed'       => today()->subDay()->toDateString(),
        'codigoIps'     => \App\Models\RedServicios\RsEntidade::find(1)->id,
        'orgAten'       => \App\Models\Autorizaciones\AuOrigenAtencion::find(1)->id,
        'serSol'        => \App\Models\RedServicios\RsServicio::find(1)->codigo,
        'prior'         => \App\Models\General\GnSiNo::find(1)->id,
        'ubic'          => \App\Models\Autorizaciones\RefUbic::find(1)->codigo,
        'serv'          => $faker->text(50),
        'cama'          => $faker->text(10),
        'jusCli'        => $faker->text(100),
        'gn_archivo_id'    => \App\Models\General\GnArchivo::first()->id,
        'dPrin'         => \App\Models\RedServicios\RsCie10::first()->codigo,
        'dRel1'         => \App\Models\RedServicios\RsCie10::find(2)->codigo,
        'dRel2'         => \App\Models\RedServicios\RsCie10::find(3)->codigo,
        'as_afiliado_id'=> \App\Models\Aseguramiento\AsAfiliado::first()->id,
        'fS'            => $faker->date(),
        'gs_usuario_id' => \App\User::first()->id,
        'fS1'           => today()->toDateString(),
        'oriAut'        => \App\Models\Autorizaciones\Reforiate::first()->codigo,
        'viaSol'        => \App\Models\Autorizaciones\Refviasol::first()->codigo,
        'au_medico_id'  => \App\Models\Autorizaciones\AuMedicosSolicitante::first()->id,
        'tInd'          => $faker->numerify('##'),
        'tNum'          => $faker->numerify('#########'),
        'tExt'          => $faker->numerify('###'),
        'tCel'          => $faker->numerify('#########'),
        'lesp'          => \App\Models\Autorizaciones\Refespecialidad::first()->codigo,
        'docs'          => $faker->numerify('######'),
        'usuReg'        => \App\User::first()->id,
        'ind'           => 1
    ];
});
