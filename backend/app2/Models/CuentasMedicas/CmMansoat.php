<?php

namespace App\Models\CuentasMedicas;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class CmMansoat extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = ['codigo', 'nombre', 'unidades', 'grupo_qx'];
    protected $searchable = ['codigo', 'nombre', 'unidades', 'grupo_qx', 'search'];

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only

        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where('codigo', $constraint->getOperator(), $constraint->getValue())
                ->orWhere('nombre', $constraint->getOperator(), $constraint->getValue())
                ->orWhere('unidades', $constraint->getOperator(), $constraint->getValue())
                ->orWhere('grupo_qx', $constraint->getOperator(), $constraint->getValue());
            return true;
        }
        // default logic should be executed otherwise
        return false;
    }
}
