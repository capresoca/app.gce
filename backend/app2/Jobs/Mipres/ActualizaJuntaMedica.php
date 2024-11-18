<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 14/03/2019
 * Time: 5:28 PM
 */

namespace App\Jobs\Mipres;


use App\Models\Mipres\MpComplementario;
use App\Models\Mipres\MpJuntaProfesional;
use App\Models\Mipres\MpMedicamento;
use App\Models\Mipres\MpNutricional;
use App\Models\Mipres\MpPrescripcione;

abstract class ActualizaJuntaMedica
{
    public static function actualizar(Array $juntaProfesional)
    {
        $prescripcion = MpPrescripcione::where('NoPrescripcion',$juntaProfesional['NoPrescripcion'])->first();

        if(!$prescripcion) return null;

        MpJuntaProfesional::updateOrCreate(
            [
                'NoPrescripcion'    => $juntaProfesional['NoPrescripcion'],
                'TipoTecnologia'    => $juntaProfesional['TipoTecnologia'],
                'Consecutivo'       => $juntaProfesional['Consecutivo']
            ],
            [
                'FPrescripcion'         => $juntaProfesional['FPrescripcion'],
                'EstJM'                 => $juntaProfesional['EstJM'],
                'CodEntProc'            => $juntaProfesional['CodEntProc'],
                'Observaciones'         => $juntaProfesional['Observaciones'],
                'JustificacionTecnica'  => $juntaProfesional['JustificacionTecnica'],
                'Modalidad'             => $juntaProfesional['Modalidad'],
                'NoActa'                => $juntaProfesional['NoActa'],
                'FechaActa'             => $juntaProfesional['FechaActa'],
                'FProceso'              => $juntaProfesional['FProceso'],
                'TipoIDPaciente'        => $juntaProfesional['TipoIDPaciente'],
                'NroIDPaciente'         => $juntaProfesional['NroIDPaciente'],
                'CodEntJM'              => $juntaProfesional['CodEntJM'],
                'mp_prescripcion_id'    => $prescripcion->id,
                'mp_medicamento_id'     => static::getIdMedicamento($juntaProfesional,$prescripcion),
                'mp_complementario_id'  => static::getIdComplementario($juntaProfesional,$prescripcion),
                'mp_nutricional_id'     => static::getIdNutricional($juntaProfesional, $prescripcion)
            ]
        );
    }

    private static function getIdMedicamento($juntaProfesional,$prescripcion)
    {
        if($juntaProfesional['TipoTecnologia'] != 'M') return null;

        $medicamento = MpMedicamento::where([
            'mp_prescripcion_id'=> $prescripcion->id,
            'ConOrden' => $juntaProfesional['Consecutivo']
        ])->first();

        if(!$medicamento) return null;

        $medicamento->EstJM = $juntaProfesional['EstJM'];

        $medicamento->save();

        return $medicamento->id;
    }

    private static function getIdComplementario($juntaProfesional,$prescripcion)
    {
        if($juntaProfesional['TipoTecnologia'] != 'S') return null;

        $complementario = MpComplementario::where([
            'mp_prescripcion_id'=> $prescripcion->id,
            'ConOrden'          => $juntaProfesional['Consecutivo']
        ])->first();

        if(!$complementario) return null;
        $complementario->EstJM = $juntaProfesional['EstJM'];

        $complementario->save();
        return $complementario->id;
    }
    private static function getIdNutricional($juntaProfesional,$prescripcion)
    {
        if($juntaProfesional['TipoTecnologia'] != 'N') return null;

        $nutricional = MpNutricional::where([
            'mp_prescripcion_id'=> $prescripcion->id,
            'ConOrden'          => $juntaProfesional['Consecutivo']
        ])->first();

        if(!$nutricional) return null;

        $nutricional->EstJM = $juntaProfesional['EstJM'];

        $nutricional->save();
        return $nutricional->id;
    }
}