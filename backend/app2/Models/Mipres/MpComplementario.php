<?php

namespace App\Models\Mipres;

use Illuminate\Database\Eloquent\Model;

class MpComplementario extends Model
{
    protected $fillable = [
        'ConOrden',
        'TipoPrest',
        'CausaS1',
        'CausaS2',
        'CausaS3',
        'CausaS4',
        'DescCausaS4',
        'CausaS5',
        'CodSerComp',
        'DescSerComp',
        'CanForm',
        'CadaFreUso',
        'CodFreUso',
        'Cant',
        'CantTotal',
        'CodPerDurTrat',
        'JustNoPBS',
        'IndRec',
        'EstJM',
        'mp_prescripcion_id',
        'mp_tutela_id'
    ];

    protected $appends = [
        'FrecuenciaUso',
        'DuracionTratamiento',
        'TipoPrestador',
        'EstadoJunta',
        'direccionado'
    ];


    public function servicio()
    {
        return $this->belongsTo(MpTipsercomplementario::class, 'CodSerComp','codigo');
    }

    public function servicioTu()
    {
        return $this->belongsTo(MpSercomplementariostu::class,'CodSerComp','codigo');
    }

    public function direccionamientos()
    {
        return $this->hasMany(MpDireccionamiento::class, 'mp_coplementario_id');
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
            '7' => 'Según respuesta al tratamiento',
            '8' => 'Única'
        ][$this->CodFreUso];
    }

    public function getDuracionTratamientoAttribute()
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

        return [
            '1' => 'No requiere junta de profesionales',
            '2' => 'Requiere junta de profesionales y pendiente evaluación',
            '3' => 'Evaluada por la junta de profesionales y fue aprobada',
            '4' => 'Evaluada por la junta de profesionales y no fue aprobada'

        ][$this->EstJM];
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
