<?php

use App\Models\Aseguramiento\AsAfiliado;
use Faker\Generator as Faker;

$factory->define(\App\Models\AtencionUsuario\AuPqrsd::class, function (Faker $faker) {
    $afiliado = AsAfiliado::where('gn_municipio_id',400)->limit(50)->get()->random();
    $fecha_recepcion = $faker->dateTimeThisMonth('now')->format('Y-m-d');
    $fecha_limite = \Carbon\Carbon::createFromFormat('Y-m-d',$fecha_recepcion)->addDays(random_int(1,15));
    $plazo = \App\Traits\CarbonColombia::createFromFormat('Y-m-d',$fecha_recepcion)->diffInBussinessDays($fecha_limite);
    $entidades = \App\Models\RedServicios\RsEntidade::select('id')->limit(100)->get()->pluck('id');
    return [
        'tipo_usuario' => $faker->randomElement(['Afiliado','Otro']),
        'au_tipopqrsd_id' => \App\Models\AtencionUsuario\AuTipopqrsd::all()->random()->id,
        'au_motivo_id' => \App\Models\AtencionUsuario\AuMotivo::all()->random()->id,
        'funcionario_id' => \App\User::all()->random()->id,
        'nombres' => $afiliado->nombre1.' ' .$afiliado->nombre2,
        'apellidos' => $afiliado->apellido1.' ' .$afiliado->apellido2,
        'identificacion' => $afiliado->identificacion,
        'gn_tipdocidentidad_id' => $afiliado->gn_tipdocidentidad_id,
        'direccion' => $faker->address,
        'telefono' => $faker->numerify('##########'),
        'gn_municipio_id' =>  $faker->randomNumber(3),
        'email' => null,
        'medio_recepcion' => $faker->randomElement(['Personalizado','Escrito','Web','Correo','Telefonico','Chat']),
        'relato' => $faker->text('500'),
        'es_prioritaria' => $faker->randomElement(['Si','No']),
        'fecha_recepcion' => $fecha_recepcion,
        'fecha_limite' => $fecha_limite->toDateString(),
        'user_id' => 1,
        'as_afiliado_id' => $afiliado->id,
        'plazo' => $plazo,
        'rs_entidad_id' => $faker->randomElement($entidades)
    ];
});
