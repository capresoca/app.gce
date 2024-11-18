<?php

namespace App\Models\RedServicios;

use App\Models\CuentasMedicas\CmCenso;
use App\Models\General\GnMunicipio;
use App\Models\Niif\GnTercero;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\ContratacionEstatal\CeProconminuta;

class RsEntidade extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;
    use SoftDeletes;

    protected $searchable = ['rs_tipentidade_id','gn_municipiosede_id','cod_habilitacion','search'];

    protected $fillable = [
        'gn_tercero_id',
        'rs_tipentidade_id',
        'nombre',
        'res_habilitacion',
        'cod_habilitacion',
        'correo_electronico_sede',
        'telefono_sede',
        'direccion_sede',
        'gn_municipiosede_id',
        'tipo_locacion',
        'gerente',
        'replegal',
        'naturaleza',
        'complejidad',
        'ive',
        'primaria',
        'portabilidad',
        'auditoria_concurrente'
    ];

    protected $with = ['tercero','municipio'];

    public function tercero(){
        return $this->belongsTo(GnTercero::class, 'gn_tercero_id');
    }

    public function tipo(){
        return $this->belongsTo(RsTipentidade::class, 'rs_tipentidade_id');
    }

    public function municipio()
    {
        return $this->belongsTo(GnMunicipio::class, 'gn_municipiosede_id');
    }

    public function servicios_habilitados()
    {
        return $this->belongsToMany(RsServicio::class, 'rs_servhabilitados','rs_entidad_id','rs_servicio_id');
    }
    
    public function minutas()
    {
        return $this->hasMany(CeProconminuta::class, 'rs_entidad_id');//return $this->belongsTo(CeProconminuta::class, 'id','rs_entidad_id');
        //return $this->belongsToMany(CeProconminuta::class, 'ce_proconminutas','rs_entidad_id','id');
        //return $this->belongsToMany(CeProconminuta::class, 'rs_servhabilirs_entidad_idtados','rs_entidad_id','rs_servicio_id');
    }

    public function servicios_generales()
    {
        return $this->belongsToMany(
            RsServicio::class,
            'rs_servhabilitados',
            'rs_entidad_id',
            'rs_servicio_id')
            ->where('primario','=',0);
    }

    public function servicios_primarios()
    {
        return $this->belongsToMany(RsServicio::class, 'rs_servhabilitados','rs_entidad_id','rs_servicio_id')
            ->wherePrimario(1);
    }

    public function procedimientos()
    {
        return $this->belongsToMany(RsCups::class,'rs_cupsentidades','rs_entidad_id','rs_cups_id');
    }

    public function medicamentos()
    {
        return $this->belongsToMany(RsCum::class, 'rs_cumentidades','rs_entidad_id','rs_cum_id');
    }

    public function setCodHabilitacionAttribute($value)
    {
        if($this->rs_tipentidade_id !== 1 && $this->rs_tipentidade_id !== 6 && $this->tercero && !$this->cod_habilitacion){
            $this->attributes['cod_habilitacion'] = $this->tercero->tipo_id->abreviatura . $this->tercero->identificacion;
        } else{
            $this->attributes['cod_habilitacion'] = $value;
        }
    }

    public function otros()
    {
        return $this->hasMany(RsOtrosentidade::class, 'rs_entidades_id');
    }

    public function capinstaladas()
    {
        return $this->hasMany(RsCapinstalada::class,'rs_entidades_id');
    }

    public function censos()
    {
        return $this->hasMany(CmCenso::class,'ips_id');
    }


    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function ($query) use ($constraint){
                    $query->whereHas('tercero', function ($query) use ($constraint) {
                        $query->where('identificacion', $constraint->getOperator(),$constraint->getValue());
                    })->orWhere('cod_habilitacion',$constraint->getOperator(), $constraint->getValue())
                    ->orWhere('nombre', $constraint->getOperator(), $constraint->getValue());
            });
            return true;
        }

        // default logic should be executed otherwise
        return false;
    }
}



