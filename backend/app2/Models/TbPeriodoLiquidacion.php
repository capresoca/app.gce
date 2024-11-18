<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;

class TbPeriodoLiquidacion extends Model
{
    use PimpableTrait;

    protected $table = 'tb_periodo_liquidacion';
    protected $primaryKey = 'consecutivo_liquidacion';
    protected $fillable = ['descripcion','fecha_inicio','fecha_fin','estado'];
    public $timestamps = false;
    protected $searchable = ['search'];

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where('consecutivo_liquidacion','like', $constraint->getValue());
            return true;
        }

        // default logic should be executed otherwise
        return false;
    }
}
