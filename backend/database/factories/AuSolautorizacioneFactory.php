<?php

use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsRegimene;
use App\Models\Autorizaciones\AuAutorizacione;
use App\Models\Autorizaciones\AuModservicio;
use App\Models\Autorizaciones\AuServicio;
use App\Models\Autorizaciones\AuSolautorizacione;
use App\Models\RedServicios\RsCie10;
use App\Models\RedServicios\RsEntidade;
use App\Models\RedServicios\RsPlancobertura;
use Faker\Generator as Faker;

$factory->define(AuSolautorizacione::class, function (Faker $faker) {
    return [
        'tipo_aprobacion' => $faker->randomElements(['Pertinencia Medica', 'Derechos del Afiliado']),
        'rs_plancobertura_id' => RsPlancobertura::all()->random()->id,
        'fecha' => $faker->dateTime(),
        'fecha_ordmedica' => $faker->date(),
        'au_autorizacion_id' => AuAutorizacione::all()->random()->id,
        'as_afiliado_id' => AsAfiliado::limit(100)->get()->random()->id,
        'as_regimen_id' => AsRegimene::all()->random()->id,
        'tipo_origen' => $faker->randomElement(['Ambulatorio','Hospitalario','Urgencias']),
        'rs_entorigen_id' => RsEntidade::all()->random()->id,
        'rs_cie10_id' => RsCie10::all()->random()->id,
        'rs_entdestino_id' => RsEntidade::all()->random()->id,
        'au_modservicio_id' => AuModservicio::all()->random()->id,
        'au_servicio_id' => AuServicio::all()->random()->id,
        'alto_costo' => $faker->randomElement(['Si','No']),
        'tutela' => $faker->randomElement(['Si','No']),
        'tipo_autorizacion' => $faker->randomElement(['Ambulatoria', 'Urgencia', 'Hospitalaria', 'Referencia', 'Alto Costo', 'Tutela']),
        'gs_usuario_id' => 1,
        'gs_usuprocesa_id' => 1,
        'observaciones' => $faker->text(),
        'estado' => $faker->randomElement(['Registrada', 'En Estudio', 'Aprobada', 'Aprobada Parcialmente', 'Negada', 'Anulada']),
    ];
});
