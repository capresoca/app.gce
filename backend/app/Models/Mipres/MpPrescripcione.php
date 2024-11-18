<?php

namespace App\Models\Mipres;

use App\Models\Aseguramiento\AsAfiliado;
use App\Models\General\GnMunicipio;
use App\Models\RedServicios\RsCie10;
use App\Models\RedServicios\RsEntidade;
use App\Models\RedServicios\RsEntidadesBase;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;

class MpPrescripcione extends Model
{
    use PimpableTrait;

    protected $fillable = [
        'NoPrescripcion',
        'FPrescripcion',
        'HPrescripcion',
        'CodHabIPS',
        'TipoIDIPS',
        'NroIDIPS',
        'CodDANEMunIPS',
        'DirSedeIPS',
        'TelSedeIPS',
        'TipoIDProf',
        'NumIDProf',
        'PNProfS',
        'SNProfS',
        'PAProfS',
        'SAProfS',
        'RegProfS',
        'TipoIDPaciente',
        'NroIDPaciente',
        'PNPaciente',
        'SNPaciente',
        'PAPaciente',
        'SAPaciente',
        'CodAmbAte',
        'EnfHuerfana',
        'CodEnfHuerfana',
        'EnfHuerfanaDX',
        'CodDxPpal',
        'CodDxRel1',
        'CodDxRel2',
        'SopNutricional',
        'CodEPS',
        'TipoIDMadrePaciente',
        'NroIDMadrePaciente',
        'TipoTransc',
        'TipoIDDonanteVivo',
        'NroIDDonanteVivo',
        'EstPres',
        'rs_entidad_id',
        'as_afiliado_id',
        'ips',
        'paciente',
        'estado',
        'regimen'
    ];

    protected $appends = ['AmbitoAtencion', 'TipoTranscripcion', 'EstadoPrescripcion'];

    protected $searchable = ['search', 'FPrescripcion', 'CodAmbAte', 'TipoTransc', 'estado', 'EstPres', 'as_afiliado_id'];

    public function medicamentos()
    {
        return $this->hasMany(MpMedicamento::class, 'mp_prescripcion_id');
    }

    public function procedimientos()
    {
        return $this->hasMany(MpProcedimiento::class, 'mp_prescripcion_id');
    }

    public function dispositivos()
    {
        return $this->hasMany(MpDispositivo::class, 'mp_prescripcion_id');
    }

    public function nutricionales()
    {
        return $this->hasMany(MpNutricional::class, 'mp_prescripcion_id');
    }

    public function complementarios()
    {
        return $this->hasMany(MpComplementario::class, 'mp_prescripcion_id');
    }

    public function novedades()
    {
        return $this->hasMany(MpNovedade::class, 'mp_prescripcion_id');
    }

    public function novedadGeneradora()
    {
        return $this->hasOne(MpNovedade::class, 'mp_prescripcionFinal_id');
    }

    public function juntas()
    {
        return $this->hasMany(MpJuntaProfesional::class, 'mp_prescripcion_id');
    }

    public function farmacia()
    {
        return $this->belongsTo(RsEntidade::class, 'farmacia_id');
    }

    public function scopeWithAll($query)
    {
        return $query->with($this->allRelations());
    }


    public function getAmbitoAtencionAttribute()
    {
        if (!$this->CodAmbAte) return null;

        $ambitos = [
            '11' => 'Ambulatorio – Priorizado',
            '12' => 'Ambulatorio – No Priorizado',
            '21' => 'Hospitalario – Domiciliario',
            '22' => 'Hospitalario - Internación',
            '30' => 'Urgencias',
        ];

        return $ambitos[$this->CodAmbAte];
    }

    public function getTipoTranscripcionAttribute()
    {
        if (!$this->TipoTransc) return null;

        $transcripciones = [
            '1' => 'Contingencia - Dificultades técnicas',
            '2' => 'Contingencia - No hay servicio eléctrico',
            '3' => 'Contingencia - No hay conectividad',
            '4' => 'Contingencia - Inconsistencia en afiliación o identificación',
            '5' => 'Urgencia Médica',
            '6' => 'Donante no efectivo',
        ];

        return $transcripciones[$this->TipoTransc];
    }

    public function getEstadoPrescripcionAttribute()
    {
        if (!$this->EstPres) return null;

        $estados = [
            '1' => 'Modificado',
            '2' => 'Anulado',
            '4' => 'Activo'
        ];

        return $estados[$this->EstPres];
    }

    public function direccionado()
    {
        if ($this->countItems() == 0) return 0;

        return ($this->medicamentos->sum('direccionado')
                + $this->nutricionales->sum('direccionado')
                + $this->procedimientos->sum('direccionado')
                + $this->dispositivos->sum('direccionado')
                + $this->complementarios->sum('direccionado')) * (1 / $this->countItems());
    }

    public function countItems()
    {
        return $this->medicamentos->count()
            + $this->nutricionales->count()
            + $this->procedimientos->count()
            + $this->dispositivos->count()
            + $this->complementarios->count();
    }

    public function setHPrescripcionAttribute($value)
    {
        $this->attributes['HPrescripcion'] = substr($value, 0, 8);
    }

    public function afiliado()
    {
        return $this->belongsTo(AsAfiliado::class, 'as_afiliado_id');
    }

    public function entidad()
    {
        return $this->belongsTo(RsEntidade::class, 'rs_entidade_id');
    }

    public function getIdentificacionAttribute()
    {
        return $this->TipoIDPaciente . ' ' . $this->NroIDPaciente;
    }

    public function DxPrincipal()
    {
        return $this->belongsTo(RsCie10::class, 'CodDxPpal', 'codigo');
    }

    public function Dx1()
    {
        return $this->belongsTo(RsCie10::class, 'CodDxRel1', 'codigo');
    }

    public function Dx2()
    {
        return $this->belongsTo(RsCie10::class, 'CodDxRel2 ', 'codigo');
    }

    public function municipioIps()
    {
        return $this->belongsTo(GnMunicipio::class, 'CodDANEMunIPS', 'codigo');
    }

    public function allRelations()
    {
        return [
            'medicamentos.PrincipiosActivos.principio_activo',
            'medicamentos.PrincipiosActivos.unimedida_concentracion',
            'medicamentos.PrincipiosActivos.unimedida_medicamento',
            'medicamentos.IndicacionesUnirs',
            'medicamentos.UnidadMedidaDosis',
            'medicamentos.ViaAdministracion',
            'medicamentos.FormaFarmaceutica',
            'medicamentos.presentacion',
            'medicamentos.direccionamientos.entidad.municipio',
            'complementarios.direccionamientos.entidad.municipio',
            'nutricionales.direccionamientos.entidad.municipio',
            'dispositivos.direccionamientos.entidad.municipio',
            'procedimientos.direccionamientos.entidad.municipio',
            'procedimientos.cup', 'procedimientos.procedimiento_utilizado',
            'procedimientos.procedimiento_descartado', 'dispositivos.dispositivo',
            'nutricionales.producto',
            'nutricionales.UnidadMedidaDosis',
            'nutricionales.forma',
            'nutricionales.formaCantidadTotal',
            'nutricionales.tipo',
            'complementarios.servicio',
            'novedades',
            'novedadGeneradora',
            'afiliado',
            'entidad',
            'DxPrincipal',
            'Dx1',
            'Dx2',
            'municipioIps',
            'juntas'
        ];
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only

        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function ($query) use ($builder, $constraint) {
                $search = $constraint->getValue();
                if ($constraint->getOperator() === Constraint::OPERATOR_LIKE) {
                    $search = substr($constraint->getValue(), 1, -1);
                }
                $query->where('NoPrescripcion', $search)->orWhere('NroIDPaciente', $search);
                if (!is_numeric($search)) {
                    $query->orWhere('ips', 'like', '%' . $search . '%')->orWhere('paciente', 'like', '%' . $search . '%');
                }
            });

            return true;

        }


        // default logic should be executed otherwise
        return false;
    }

}


