<?php

namespace App\Models\Mipres;

use Illuminate\Database\Eloquent\Model;

class MpNutricional extends Model
{
    protected $fillable = [
        'mp_prescripcion_id',
        'mp_tutela_id',
        'ConOrden',
        'TipoPrest',
        'CausaS1',
        'CausaS2',
        'CausaS3',
        'CausaS4',
        'ProNutUtilizado',
        'RznCausaS41',
        'DescRzn41',
        'RznCausaS42',
        'DescRzn42',
        'CausaS5',
        'ProNutDescartado',
        'RznCausaS51',
        'DescRzn51',
        'RznCausaS52',
        'DescRzn52',
        'RznCausaS53',
        'DescRzn53',
        'RznCausaS54',
        'DescRzn54',
        'DXEnfHuer',
        'DXVIH',
        'DXCaPal',
        'DXEnfRCEV',
        'TippProNut',
        'DescProdNutr',
        'CodForma',
        'CodViaAdmon',
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
        'NoPrescAso',
        'EstJM'
    ];

    protected $appends = [
        'tipo_prestador',
        'via_administracion',
        'frecuencia_administracion',
        'indicaciones_especiales',
        'duracion_tratamiento',
        'DxEnfermedadHuerfana',
        'DxVIH',
        'DxCancerPaliativo',
        'DxEnfermedadRenalCronica',
        'EstadoJunta',
        'direccionado'
    ];

    protected $estadosDx = [
        '0' =>'Diagnóstico No Confirmado',
        '1' =>'Diagnóstico Confirmado'
    ];

    public function getTipoPrestadorAttribute()
    {
        if(!$this->TipoPrest) return null;

        $tipos = [
            '1' => 'Única',
            '2' => 'Sucesiva'
        ];

        return $tipos[$this->TipoPrest];
    }

    public function tipo()
    {
        return $this->belongsTo(MpTipnutricional::class, 'TippProNut','codigo');
    }
    public function direccionamientos()
    {
        return $this->hasMany(MpDireccionamiento::class, 'mp_nutricional_id');
    }
    public function producto()
    {
        return $this->belongsTo(MpProdnutricionale::class,'DescProdNutr','codigo');
    }

    public function forma()
    {
        return $this->belongsTo(MpFormprodnutricionale::class,'CodForma','codigo');
    }

    public function formaCantidadTotal()
    {
        return $this->belongsTo(MpFormprodnutricionale::class,'UFCantTotal','codigo');
    }

    public function UnidadMedidaDosis()
    {
        return $this->belongsTo(MpUnimedida::class,'DosisUM','codigo');
    }

    public function getViaAdministracionAttribute()
    {
        if(!$this->CodViaAdmon) return null;

        $vias = [
            '1' => 'Oral',
            '2' => 'Sonda'
        ];

        return $vias[$this->CodViaAdmon];
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

    public function getDxEnfermedadHuerfanaAttribute()
    {
        if(!isset($this->DXEnfHuer)) return 'Sin Diagnóstico';

        return $this->estadosDx[$this->DXEnfHuer];
    }

    public function getDxVIHAttribute()
    {
        if(!isset($this->DXEnfHuer)) return 'Sin Diagnóstico';

        return $this->estadosDx[$this->DXEnfHuer];
    }

    public function getDxCancerPaliativoAttribute()
    {
        if(!isset($this->DXEnfHuer)) return 'Sin Diagnóstico';

        return $this->estadosDx[$this->DXEnfHuer];
    }

    public function getDxEnfermedadRenalCronicaAttribute()
    {
        if(!isset($this->DXEnfHuer)) return 'Sin Diagnóstico';

        return $this->estadosDx[$this->DXEnfHuer];
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
        if(!$this->CantTotalF) return 1;
        return $this->direccionamientos->sum('CantTotAEntregar') / $this->CantTotalF;
    }



}
