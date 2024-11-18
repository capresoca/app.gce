<?php

namespace App\Models\RedServicios;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;

class RsEntidadesBase extends Model
{
    use PimpableTrait;
    protected $searchable = ['codigo','nits_nit','nombre','search'];

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where('codigo',$constraint->getOperator(), $constraint->getValue())
                ->orWhere('nits_nit',$constraint->getOperator(), $constraint->getValue())
                ->orWhere('nombre',$constraint->getOperator(), $constraint->getValue());

            return true;
        }

        // default logic should be executed otherwise
        return false;
    }

}
