<?php

namespace App\Models\Mipres;

use Illuminate\Database\Eloquent\Model;

class MpDispositivo extends Model
{
    protected $fillable = [
        'mp_prescripcion_id',
        'mp_tutela_id',
        'ConOrden',
        'TipoPrest',
        'CausaS1',
        'CodDisp',
        'CanForm',
        'CadaFreUso',
        'CodFreUso',
        'Cant',
        'CodPerDurTrat',
        'CantTotal',
        'JustNoPBS',
        'IndRec',
        'EstJM'
    ];

    protected $appends = [
        'periodo_duracion_tratamiento',
        'frecuencia_uso',
        'tipo_prestador',
        'EstadoJunta',
        'direccionado'
    ];


    public function dispositivo()
    {
        return $this->belongsTo(MpTipdispositivos::class, 'CodDisp','codigo');
    }

    public function direccionamientos()
    {
        return $this->hasMany(MpDireccionamiento::class, 'mp_dispositivo_id');
    }

    public function getPeriodoDuracionTratamientoAttribute()
    {
        if(!$this->CodPerDurTrat) return null;

        $duraciones = [
            '1' => 'Minuto(s)',
            '2' => 'Hora(s)',
            '3' => 'Día(s)',
            '4' => 'Semana(s)',
            '5' => 'Mes(es)',
            '6' => 'Año'
        ];

        return $duraciones[$this->CodPerDurTrat];
    }

    public function getFrecuenciaUsoAttribute()
    {
        if(!$this->CodFreUso) return null;
        $codigos = [
            '1' => 'Minuto(s)',
            '2' => 'Hora(s)',
            '3' => 'Día(s)',
            '4' => 'Semana(s)',
            '5' => 'Mes(es)',
            '6' => 'Año',
            '7' => 'Según respuesta al tratamiento',
            '8' => 'Única'
        ];

        return $codigos[$this->CodFreUso];
    }

    public function getTipoPrestadorAttribute()
    {
        if(!$this->TipoPrest) return null;

        $tipos = [
            '1' => 'Única',
            '2' => 'Sucesiva'
        ];

        return $tipos[$this->TipoPrest];
    }

    public function getEstadoJuntaAttribute()
    {
        if(!$this->EstJM) return null;

        $estados = [
            '1' => 'No requiere junta de profesionales',
            '2' => 'Requiere junta de profesionales y pendiente evaluación',
            '3' => 'Evaluada por la junta de profesionales y fue aprobada',
            '4' => 'Evaluada por la junta de profesionales y no fue aprobada'

        ];

        return $estados[$this->EstJM];
    }

    public function prescripcion()
    {
        return $this->belongsTo(MpPrescripcione::class,'mp_prescripcion_id');
    }


    public function getDireccionadoAttribute()
    {
        if(!$this->CantTotal) return 1;
        return $this->direccionamientos->sum('CantTotAEntregar') / $this->CantTotal;
    }


}