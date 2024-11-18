<?php

namespace App\Models\LiGestiones;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;

class LiAuditoria extends Model
{
    use PimpableTrait;

    protected $primaryKey = 'consecutivo_auditoria';
    protected $fillable = ['concepto', 'consecutivo_licencia', 'estado_auditoria', 'fecha_grabado', 'usuario_grabado', 'valor_liquidacion'];
    protected $searchable = ['search'];

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where('consecutivo_auditoria','like', $constraint->getValue());
            return true;
        }

        // default logic should be executed otherwise
        return false;
    }
}
