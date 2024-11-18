<?php

namespace App\Models\LiGestiones;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;

class LiAuditoriaPrevia extends Model
{
    use PimpableTrait;

    protected $primaryKey = 'secuencia';
    protected $fillable = ['consecutivo_licencia', 'consecutivo_auditoria'];
    protected $searchable = ['search'];

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where('secuencia','like', $constraint->getValue());
            return true;
        }

        // default logic should be executed otherwise
        return false;
    }
}
