<?php

namespace App\Models\Aseguramiento;

use App\Models\General\GnMunicipio;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\General\GnTipdocidentidade;

class AsNovtramite extends Model implements Auditable
{
    use PimpableTrait;
    use \OwenIt\Auditing\Auditable;

    protected $searchable = ['search'];
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];
    protected $appends = ['fecha_ini_novedad_slash'];
    
    protected $fillable = [
        'as_tramite_id',
        'as_tipnovedade_id',
        'as_afiliado_id',
        'gn_municipio_id',
        'gn_tipdocidentidad_id',
        'codigo_entidad',
        'identificacion',
        'apellido1',
        'apellido2',
        'nombre1',
        'nombre2',
        'fecha_nacimiento',
        'fecha_ini_novedad',
        'v1',
        'v2',
        'v3',
        'v4',
        'v5',
        'v6',
        'v7',
        'observaciones',
        'no_radicado',
        'consecutivo_vigencia',
        'estado_proceso',
        'tipo_regimen',
        'consecutivo_ns_procesada',
        'consecutivo_log_ns'
    ];

    /**
     * @return array
     */
    public function getFillable()
    {
        return $this->fillable;
    }

    /**
     * @param array $fillable
     */
    public function setFillable($fillable)
    {
        $this->fillable = $fillable;
    }

    public function tipo_id()
    {
        return $this->belongsTo(GnTipdocidentidade::class, 'gn_tipdocidentidad_id');
    }
    
    public function pagador()
    {
        return $this->belongsTo(AsPagadore::class,'as_pagadore_id');
    }

    public function afiliado(){
        return $this->belongsTo(AsAfiliado::class,'as_afiliado_id');
    }

    public function tramite()
    {
        return $this->belongsTo(AsTramite::class,'as_tramite_id');
    }

    public function novedad()
    {
        return $this->belongsTo(AsTipnovedade::class, 'as_tipnovedade_id');
    }

    public function municipio () {
        return $this->belongsTo(GnMunicipio::class,'gn_municipio_id');
    }

    public function getEstadoProcesoAttribute($key)
    {
        $name = null;
        switch ($this->attributes['estado_proceso']) {
            case 0: $name = 'Pendiente Respuesta'; break;
            case 1: $name = 'Si'; break;
            case 2: $name = 'Pendiente NEG'; break;
            case 3: $name = 'Anulado'; break;
            case 4: $name = 'Resuelto'; break;
            case 5: $name = 'Pendiente Respuesto'; break;
        }

        return $name;
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only

        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->whereHas('afiliado', function ($query) use ($constraint){
                $query->where('nombre1', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('nombre2', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('apellido1', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('apellido2', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('identificacion', $constraint->getOperator(), $constraint->getValue());

            });

            return true;
        }

        // default logic should be executed otherwise
        return false;
    }

    public function getFechaIniNovedadSlashAttribute()
    {
        $fecha = new Carbon($this->fecha_ini_novedad);

        return $fecha->format('d/m/Y');
    }
    
    
}
