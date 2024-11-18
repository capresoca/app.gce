<?php

namespace App\Models\LiGestiones;
use Illuminate\Database\Eloquent\Builder;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;

use Illuminate\Database\Eloquent\Model;

class LiAuditoriaPeriodo extends Model
{
    use PimpableTrait;

    protected $primaryKey = 'consecutivo_periodo';
    protected $fillable = ['consecutivo_envio', 'consecutivo_auditoria', 'dias', 'dias_reconocer', 'fecha_pago', 'valor'];
    protected $searchable = ['search'];

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where('consecutivo_periodo','like', $constraint->getValue());
            return true;
        }

        // default logic should be executed otherwise
        return false;
    }
}
