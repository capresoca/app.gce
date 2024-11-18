<?php

namespace App\Models\RedServicios;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class RsCie10 extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = [
        'codigo',
        'codigo_tres',
        'descripcion',
        'genero',
        'edad_min',
        'edad_max'
    ];
    protected $hidden = ['created_at', 'updated_at'];
    protected $searchable = ['codigo', 'codigo_tres', 'genero', 'edad_min', 'edad_max', 'search'];

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only

        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where('codigo', 'like', strtoupper($constraint->getValue()))
                ->orWhere('codigo_tres', $constraint->getOperator(), $constraint->getValue())
                ->orWhere('descripcion', $constraint->getOperator(), $constraint->getValue())
                ->orWhere('genero', $constraint->getOperator(), $constraint->getValue())
                ->orWhere('edad_min', $constraint->getOperator(), $constraint->getValue())
                ->orWhere('edad_max', $constraint->getOperator(), $constraint->getValue());
            return true;
        }
        // default logic should be executed otherwise
        return false;
    }

}
