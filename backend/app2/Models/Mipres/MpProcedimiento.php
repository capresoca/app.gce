<?php

namespace App\Models\Mipres;

use App\Models\RedServicios\RsCups;
use Illuminate\Database\Eloquent\Model;

class MpProcedimiento extends Model
{
    protected $fillable = [
        'mp_prescripcion_id',
        'mp_tutela_id',
        'rs_cups_id',
        'ConOrden',
        'TipoPrest',
        'CausaS11',
        'CausaS12',
        'CausaS2',
        'CausaS3',
        'CausaS4',
        'ProPBSUtilizado',
        'CausaS5',
        'ProPBSDescartado',
        'RznCausaS51',
        'DescRzn51',
        'RznCausaS52',
        'DescRzn52',
        'CausaS6',
        'CausaS7',
        'CodCUPS',
        'CanForm',
        'CadaFreUso',
        'CodFreUso',
        'Cant',
        'CantTotal',
        'CodPerDurTrat',
        'JustNoPBS',
        'IndRec',
        'EstJM'
    ];

    protected $appends = [
        'periodo_duracion_tratamiento',
        'frecuencia_uso',
        'tipo_prestador',
        'estado_junta',
        'direccionado'
    ];

    public function cup()
    {
        return $this->belongsTo(RsCups::class,'CodCUPS','codigo');
    }

    public function prescripcion()
    {
        return $this->belongsTo(MpPrescripcione::class,'mp_prescripcion_id');
    }

    public function procedimiento_utilizado()
    {
        return $this->belongsTo(RsCups::class,'ProPBSUtilizado','codigo');
    }

    public function procedimiento_descartado()
    {
        return $this->belongsTo(RsCups::class,'ProPBSDescartado','codigo');
    }

    public function direccionamientos()
    {
        return $this->hasMany(MpDireccionamiento::class, 'mp_procedimiento_id');
    }

    public function getDireccionadoAttribute()
    {
        if(!$this->CantTotal) return 1;
        return $this->direccionamientos->sum('CantTotAEntregar') / $this->CantTotal;
    }


    public function getPeriodoDuracionTratamientoAttribute()
    {
        if(!$this->CodPerDurTrat) return null;

        return [
            '1' => 'Minuto(s)',
            '2' => 'Hora(s)',
            '3' => 'Día(s)',
            '4' => 'Semana(s)',
            '5' => 'Mes(es)',
            '6' => 'Año'
        ][$this->CodPerDurTrat];
    }

    public function getFrecuenciaUsoAttribute()
    {
        if(!$this->CodFreUso) return null;
        return [
            '1' => 'Minuto(s)',
            '2' => 'Hora(s)',
            '3' => 'Día(s)',
            '4' => 'Semana(s)',
            '5' => 'Mes(es)',
            '6' => 'Año',
            '8' => 'Única'
        ][$this->CodFreUso];
    }

    public function getTipoPrestadorAttribute()
    {
        if(!$this->TipoPrest) return null;

        return [
            '1' => 'Única',
            '2' => 'Sucesiva'
        ][$this->TipoPrest];
    }
    public function getEstadoJuntaAttribute()
    {
        if(!$this->EstJM) return null;

        return  [
            '1' => 'No requiere junta de profesionales',
            '2' => 'Requiere junta de profesionales y pendiente evaluación',
            '3' => 'Evaluada por la junta de profesionales y fue aprobada',
            '4' => 'Evaluada por la junta de profesionales y no fue aprobada'

        ][$this->EstJM];
    }

}

