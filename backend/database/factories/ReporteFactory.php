<?php

use App\Models\Reportes\Reporte;
use Faker\Generator as Faker;

$factory->define(Reporte::class, function (Faker $faker) {
    return [
        'nombre'            => 'prueba',
        'sentencia'         =>"select as_pagadores.razon_social as pagador, as_afiliado_pagador.ibc as ibc from as_afiliado_pagador join as_pagadores on as_pagadores.id = as_afiliado_pagador.as_pagador_id where ibc > #var1 order by ibc desc",
        'descripcion'       => $faker->text,
        'gs_modulo_id'      => \App\GsModulo::all()->random()->id,
        'gs_usuario_id'     => \App\User::all()->random()->id,

        'variables' => [
            [
                'ref' => 'ref',
                'type' => 'tipo',
                'label' => 'label'
            ]
        ],

        'roles' => [1,12]
    ];
});



