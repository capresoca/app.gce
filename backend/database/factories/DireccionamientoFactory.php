<?php

use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(\App\Models\Mipres\MpDireccionamiento::class, function (Faker $faker) {

    $medicamento = \App\Models\Mipres\MpMedicamento::orderBy(DB::raw('RAND()'))->limit(1)->first();

    $farmacia = \App\Models\RedServicios\RsEntidade::find(1763);

    return [
        'mp_prescripcion_id' => $medicamento->mp_prescripcion_id,
        'NoPrescripcion' => $medicamento->prescripcion->NoPrescripcion,
        'TipoTec' => 'M',
        'ConTec' => $medicamento->ConOrden,
        'TipoIDPaciente' => $medicamento->prescripcion->TipoIDPaciente,
        'NoIDPaciente' => $medicamento->prescripcion->NroIDPaciente,
        'as_afiliado_id' => $medicamento->prescripcion->as_afiliado_id,
        'NoEntrega' => 1,
        'NoSubEntrega' => 0,
        'TipoIdProv' => 'NI',
        'NoIDProv' => $farmacia->tercero->identificacion,
        'rs_entidade_id' => $farmacia->id,
        'CodMunEnt' => $farmacia->municipio->codigo,
        'gn_munentidad_id' => $farmacia->gn_municipiosede_id,
        'FecMaxEnt' => \Carbon\Carbon::parse($medicamento->prescripcion->FPrescripcion)->addDays(2)->toDateString(),
        'CantTotAEntregar' => $medicamento->CantTotalF,
        'DirPaciente' => $medicamento->prescripcion->afiliado->direccion,
        'CodSerTecAEntregar' => \App\Models\RedServicios\RsCum::orderBy(DB::raw('RAND()'))->limit(1)->first(),
        'mp_medicamento_id' => $medicamento->id
    ];
});
