<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Autorizaciones\AuAnexot31::class, function (Faker $faker) {
    $cup = \App\Models\Autorizaciones\Refcup::where('codigo','890601')->first();
    $fautori = $faker->date();
    return [
        'codigo'        => $cup->codigo,
        'descrip'       => $cup->descrip,
        'cant'          => $faker->numberBetween(5,10),
        'cAut'          => $faker->numberBetween(1,5),
        'gs_usuario_id' => \App\User::find(1)->id,
        'fS'            => $fautori,
        'pAut'          => \App\Models\RedServicios\RsEntidade::find(1)->id,
        'nivel'         => $faker->numberBetween(1,3),
        'modSer'        => \App\Models\Autorizaciones\Refmodalidadservicio::where('codigo',2)->first()->codigo,
        'tipModSer'     => \App\Models\Autorizaciones\Refqx::where('codigo',2)->first()->codigo,
        'cober'         => \App\Models\Autorizaciones\Refcobertura::where('codigo',1)->first()->codigo,
        'obs'           => $faker->text(),
        'espec'         => \App\Models\Autorizaciones\Refespecialidad::find($faker->numberBetween(1,120))->codigo,
        'fCad'          => \Carbon\Carbon::parse($fautori)->addMonth()->toDateString(),
        'valor'         => $faker->numerify('#######'),
        'copago'        => $faker->numerify('#######'),
        'cont'          => \App\Models\RedServicios\RsPlanescobertura::first()->id,
        'aCond'         => \App\Models\General\GnSiNo::find($faker->numberBetween(1,2)),
        'ind'           => $faker->numberBetween(1,2),
        'imp'           => 0
    ];
});