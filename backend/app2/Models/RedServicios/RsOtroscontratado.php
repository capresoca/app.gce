<?php

namespace App\Models\RedServicios;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class RsOtroscontratado extends Model implements Auditable
{
    use PimpableTrait;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['rs_otrosentidad_id','rs_contratos_id','tarifa','tarifa_entidad','descripcion','codigo','tipo_valor'];

    protected $searchable = ['search','rs_contratos_id'];

    public function plan_cobertura ()
    {
        return $this->belongsTo(RsPlanescobertura::class,'rs_contratos_id');
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only

        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where('descripcion', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('codigo', $constraint->getOperator(), $constraint->getValue());
            return true;
        }
        // default logic should be executed otherwise
        return false;
    }
}
