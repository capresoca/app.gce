<?php

use App\Models\Aseguramiento\AsAfiliado;
use App\Models\General\GnTipdocidentidade;
use App\Models\OficinaJuridica\OjTutela;
use Faker\Generator as Faker;

$factory->define(OjTutela::class, function (Faker $faker) {
    return [
        'gn_tipdocentidad_id' => GnTipdocidentidade::all()->random()->id,
        'identificacion' => $faker->numerify('########'),
        'nombre' => $faker->name(),
        'as_afiliado_id' => AsAfiliado::all()->random()->id,
        'oj_juzgado_id' => \App\Models\OficinaJuridica\OjJuzgado::all()->random()->id,
        'no_tutela' => $faker->numerify('0#######'),
        'fecha' => $faker->date(),
        'oj_pretencion_id' => \App\Models\OficinaJuridica\OjPretencione::all()->random()->id,
        'desc_pretencion' => $faker->text(),
        'tipo_tutela' => $faker->randomElement('Administrativa','POS','NO-POS'),
        'ent_accionadas' => $faker->text(),
        'med_provisional' => $faker->randomElement('Si','No'),
        'gs_usuario_id' => 1,
        'gn_archivo_id' => \App\Models\General\GnArchivo::all()->random()->id,
        'estado' => $faker->randomElement('Recibida', 'Asignada', 'Respondida', 'Fallada', 'Impugnada', 'Desacato')
    ];
});
