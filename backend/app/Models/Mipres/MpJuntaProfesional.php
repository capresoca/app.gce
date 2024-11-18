<?php

namespace App\Models\Mipres;

use Illuminate\Database\Eloquent\Model;

class MpJuntaProfesional extends Model
{
    protected $fillable = [
            'NoPrescripcion',
            'TipoTecnologia',
            'Consecutivo',
            'FPrescripcion',
            'EstJM',
            'CodEntProc',
            'Observaciones',
            'JustificacionTecnica',
            'Modalidad',
            'NoActa',
            'FechaActa',
            'FProceso',
            'TipoIDPaciente',
            'NroIDPaciente',
            'CodEntJM',
            'mp_prescripcion_id',
            'mp_medicamento_id',
            'mp_complementario_id',
            'mp_nutricional_id'
    ];
}
