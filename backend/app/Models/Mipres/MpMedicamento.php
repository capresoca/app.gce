<?php

namespace App\Models\Mipres;

use Illuminate\Database\Eloquent\Model;

class MpMedicamento extends Model
{
    protected $fillable = [
        'ConOrden',
        'TipoMed',
        'TipoPrest',
        'CausaS1',
        'CausaS2',
        'CausaS3',
        'MedPBSUtilizado',
        'RznCausaS31',
        'DescRzn31',
        'RznCausaS32',
        'DescRzn32',
        'CausaS4',
        'MedPBSDescartado',
        'RznCausaS41',
        'DescRzn41',
        'RznCausaS42',
        'DescRzn42',
        'RznCausaS43',
        'DescRzn43',
        'RznCausaS44',
        'DescRzn44',
        'CausaS5',
        'RznCausaS5',
        'CausaS6',
        'DescMedPrinAct',
        'CodFF',
        'CodVA',
        'JustNoPBS',
        'Dosis',
        'DosisUM',
        'NoFAdmon',
        'CodFreAdmon',
        'IndEsp',
        'CanTrat',
        'DurTrat',
        'CantTotalF',
        'UFCantTotal',
        'IndRec',
        'EstJM',
        'mp_prescripcion_id',
        'mp_tutela_id'
    ];

    protected $appends = [
        'IndicacionesEspeciales',
        'tipo_medicamento',
        'tipo_prestador',
        'TipificacionMedicamento',
        'FrecuenciaAdministracion',
        'DuracionTratamiento',
        'EstadoJunta',
        'direccionado'
    ];



    public function PrincipiosActivos()
    {
        return $this->hasMany(MpPrincipiosActivo::class,'mp_medicamento_id');
    }

    public function ViaAdministracion()
    {
        return $this->belongsTo(MpViasadmons::class, 'CodVA','codigo');
    }

    public function IndicacionesUnirs()
    {
        return $this->hasMany(MpIndicunirs::class, 'mp_medicamento_id');
    }

    public function presentacion()
    {
        return $this->belongsTo(MpPresentacione::class, 'UFCantTotal','codigo');
    }

    public function UnidadMedidaDosis()
    {
        return $this->belongsTo(MpUnimedida::class,'DosisUM','codigo');
    }

    public function FormaFarmaceutica()
    {
        return $this->belongsTo(MpFormfarms::class,'CodFF','codigo');
    }

    public function prescripcion()
    {
        return $this->belongsTo(MpPrescripcione::class,'mp_prescripcion_id');
    }

    public function direccionamientos()
    {
        return $this->hasMany(MpDireccionamiento::class, 'mp_medicamento_id');
    }

    public function getDireccionadoAttribute()
    {
        if(!$this->CantTotalF) return 1;
        return $this->direccionamientos->sum('CantTotAEntregar') / $this->CantTotalF;
    }


    public function getTipoMedicamentoAttribute()
    {

        if(!$this->TipoMed) return null;

        $tipos = [
            '1' => 'Medicamento',
            '2' => 'Vital No Disponible',
            '3' => 'Preparación Magistral',
            '7' => 'UNIRS4',
            '9' => 'Urgencia Médica'
        ];

        return $tipos[$this->TipoMed];
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

    public function getTipificacionMedicamentoAttribute()
    {
        if(!$this->TipMed) return null;
        $tipos = [
            '1' => 'PBS',
            '2' => 'No PBS'
        ];

        return $tipos[$this->TipMed];
    }

    public function getFrecuenciaAdministracionAttribute()
    {
        if(!$this->CodFreAdmon) return null;
        $codigos = [
            '1' => 'Minuto(s)',
            '2' => 'Hora(s)',
            '3' => 'Día(s)',
            '4' => 'Semana(s)',
            '5' => 'Mes(es)',
            '6' => 'Año',
            '7' => 'Según respuesta al tratamiento'
        ];
        
        return $codigos[$this->CodFreAdmon];
    }

    public function getIndicacionesEspecialesAttribute()
    {
        if(!$this->IndEsp) return null;

        $indicaciones = [
            '1' => 'Administración en dosis única',
            '2' => 'Administración inmediata',
            '3' => 'Administrar en Bolo',
            '4' => 'Administrar en Goteo',
            '5' => 'Infusión continua',
            '6' => 'Infusión intermitente',
            '7' => 'Infusión intermitente simultánea con perfusión de
            otra solución',
            '8' => 'Microgoteo',
            '9' => 'Perfusión',
            '10' => 'Sin indicación especial',
        ];

        return $indicaciones[$this->IndEsp];
    }

    public function getDuracionTratamientoAttribute()
    {
        if(!$this->DurTrat) return null;

        $duraciones = [
            '1' => 'Minuto(s)',
            '2' => 'Hora(s)',
            '3' => 'Día(s)',
            '4' => 'Semana(s)',
            '5' => 'Mes(es)',
            '6' => 'Año'
        ];

        return $duraciones[$this->DurTrat];
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
}



