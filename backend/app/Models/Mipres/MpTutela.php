<?php

namespace App\Models\Mipres;

use App\Models\Aseguramiento\AsAfiliado;
use App\Models\General\GnMunicipio;
use App\Models\RedServicios\RsCie10;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;

class MpTutela extends Model
{

    use PimpableTrait;

    protected $fillable =[
        'NoTutela',
        'FTutela',
        'HTutela',
        'CodEPS',
        'TipoIDEPS',
        'NroIDEPS',
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
        'NroFallo',
        'FFalloTutela',
        'F1Instan',
        'F2Instan',
        'FCorte',
        'FDesacato',
        'EnfHuerfana',
        'CodEnfHuerfana',
        'EnfHuerfanaDX',
        'CodDxPpal',
        'CodDxRel1',
        'CodDxRel2',
        'AclFalloTut',
        'CodDxMotS1',
        'CodDxMotS2',
        'CodDxMotS3',
        'JustifMed',
        'CritDef1CC',
        'CritDef2CC',
        'CritDef3CC',
        'CritDef4CC',
        'TipoIDMadrePaciente',
        'NroIDMadrePaciente',
        'EstTut',
        'paciente',
        'as_afiliado_id',
        'regimen'
    ];

    protected $searchable = ['search','FTutela','paciente'];

    protected $appends = ['EstadoTutela'];

    public function medicamentos()
    {
        return $this->hasMany(MpMedicamento::class, 'mp_tutela_id');
    }

    public function afiliado()
    {
        return $this->belongsTo(AsAfiliado::class, 'as_afiliado_id');
    }

    public function procedimientos()
    {
        return $this->hasMany(MpProcedimiento::class, 'mp_tutela_id');
    }

    public function dispositivos()
    {
        return $this->hasMany(MpDispositivo::class, 'mp_tutela_id');
    }

    public function nutricionales()
    {
        return $this->hasMany(MpNutricional::class, 'mp_tutela_id');
    }

    public function complementarios()
    {
        return $this->hasMany(MpComplementario::class, 'mp_tutela_id');
    }

    public function setHTutelaAttribute($value)
    {
        $this->attributes['HTutela'] = substr($value,0,8);
    }

    public function novedades()
    {
        return $this->hasMany(MpNovetutela::class,'mp_tutela_id');
    }

    public function direccionado()
    {
        
        if($this->countItems() <=0){
            return 0;
        }
        return ($this->medicamentos->sum('direccionado')
            + $this->nutricionales->sum('direccionado')
            + $this->procedimientos->sum('direccionado')
            + $this->dispositivos->sum('direccionado')
            + $this->complementarios->sum('direccionado')) * (1/$this->countItems());
    }

    public function countItems()
    {
        return $this->medicamentos->count()
            + $this->nutricionales->count()
            + $this->procedimientos->count()
            + $this->dispositivos->count()
            + $this->complementarios->count();
    }

    public function novedadGeneradora()
    {
        return $this->hasOne(MpNovetutela::class,'mp_tutelafinal_id');
    }

    public function DxPrincipal()
    {
        return $this->belongsTo(RsCie10::class, 'CodDxPpal','codigo');
    }

    public function Dx1()
    {
        return $this->belongsTo(RsCie10::class, 'CodDxRel1','codigo');
    }

    public function Dx2()
    {
        return $this->belongsTo(RsCie10::class, 'CodDxRel2 ','codigo');
    }

    public function dxMot1()
    {
        return $this->belongsTo(RsCie10::class, 'CodDxMotS1','codigo');
    }

    public function dxMot2()
    {
        return $this->belongsTo(RsCie10::class, 'CodDxMotS2','codigo');
    }

    public function dxMot3()
    {
        return $this->belongsTo(RsCie10::class, 'CodDxMotS3','codigo');
    }

    public function fallosAdicionales()
    {
        return $this->hasMany(MpFalloAdicional::class, 'mp_tutela_id');
    }

    public function getIdentificacionAttribute()
    {
        return $this->TipoIDPaciente.' '.$this->NroIDPaciente;
    }

    public function getEstadoTutelaAttribute()
    {
        if(!$this->EstTut) return null;

        return [
            1 => 'Modificado',
            4 => 'Activo'
        ][$this->EstTut];
    }
    

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function ($query) use($builder,$constraint){
                $search = $constraint->getValue();

                if($constraint->getOperator() === Constraint::OPERATOR_LIKE){
                    $search = substr($constraint->getValue(),1,-1);
                }
                $query->where('NoTutela', $search)->orWhere('NroIDPaciente', $search);
                if(!is_numeric($search)){
                    $query->orWhere('paciente', 'like','%'.$search.'%');
                }
            });
            return true;
        }

        // default logic should be executed otherwise
        return false;
    }

    public function allRelations()
    {
        return [
            'medicamentos.PrincipiosActivos',
            'medicamentos.IndicacionesUnirs',
            'medicamentos.UnidadMedidaDosis',
            'medicamentos.ViaAdministracion',
            'medicamentos.FormaFarmaceutica',
            'medicamentos.direccionamientos.entidad.municipio',
            'complementarios.direccionamientos.entidad.municipio',
            'nutricionales.direccionamientos.entidad.municipio',
            'dispositivos.direccionamientos.entidad.municipio',
            'procedimientos.direccionamientos.entidad.municipio',
            'procedimientos','dispositivos',
            'dispositivos.dispositivo',
            'nutricionales.producto',
            'nutricionales.UnidadMedidaDosis',
            'nutricionales.forma',
            'complementarios.servicioTu',
            'afiliado',
            'novedades',
            'novedadGeneradora',
            'DxPrincipal',
            'Dx1',
            'Dx2',
            'dxMot1',
            'dxMot2',
            'dxMot3'
        ];
    }

    public function scopeWithAll($query)
    {
        return $query->with( $this->allRelations() );
    }

}
