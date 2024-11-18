<?php

namespace App\Models\Aseguramiento;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class AsNovtramite extends Model implements Auditable
{
    use PimpableTrait;
    use \OwenIt\Auditing\Auditable;

    protected $searchable = ['search'];
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];

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
