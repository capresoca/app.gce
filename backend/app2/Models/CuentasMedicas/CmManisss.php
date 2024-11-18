<?php

namespace App\Models\CuentasMedicas;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class CmManisss extends Model implements Auditable
{
    protected $table = 'cm_manisss';

    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;
    protected $fillable = [
        'codigo', 'referencia', 'nombre', 'tipo_liquidacion', 'valor', 'uvr'
    ];
    protected $searchable = ['codigo','nombre','referencia','search'];

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only

        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where('codigo', $constraint->getOperator(), $constraint->getValue())
                ->orWhere('nombre', $constraint->getOperator(), $constraint->getValue())
                ->orWhere('referencia', $constraint->getOperator(), $constraint->getValue());
            return true;
        }
        // default logic should be executed otherwise
        return false;
    }
}
