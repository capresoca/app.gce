<?php

namespace App\Models\RedServicios;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class RsCupscontratados extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;
    protected $fillable = [
        'rs_contrato_id',
        'rs_cups_id',
        'tarifa',
        'codigo',
        'tarifa_entidad',
        'descripcion',
        'tipo_valor',
        'tipo_manual',
        'porcentaje',
        'rs_salminimo_id'
    ];

    protected $searchable = ['search','rs_contrato_id','cup:cm_mansoat_id'];

    protected $appends = ['grupo_tarifa'];

    public function plan_cobertura ()
    {
        return $this->belongsTo(RsPlanescobertura::class,'rs_contrato_id');
    }

    public function cup()
    {
        return $this->belongsTo(RsCups::class, 'rs_cups_id');
    }

    public function scopeAgrupado($query)
    {
        $query->groupBy('rs_salminimo_id','tipo_manual','porcentaje');
    }

    public function salminimo()
    {
        return $this->belongsTo(RsSalminimo::class, 'rs_salminimo_id');
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only

        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function ($query) use ($constraint){
                $query->where('descripcion', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('codigo', $constraint->getOperator(), $constraint->getValue());
            });

            return true;
        }
        // default logic should be executed otherwise
        return false;
    }

    public function getGrupoTarifaAttribute()
    {
        switch (strtoupper($this->tipo_manual)) {
            case 'SOAT':
                return $this->tipo_manual.' SMLV '.$this->salminimo['anio'].' '.$this->porcentaje.'%';

            case 'ISS':
                return $this->tipo_manual.' '.$this->porcentaje.'%';
            case 'INSTITUCIONAL':
                return $this->tipo_manual;
        }

    }

}



