<?php

namespace App\Models\AtencionUsuario;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use Illuminate\Database\Eloquent\Model;

class TbProcesoAdministrativoDetalle extends Model
{
    use PimpableTrait;

    //protected $primaryKey = 'consecutivo_soporte'; // PENDIENTE esta tabla tiene llave primaria compuesta (consecutivo_soporte, consecutivo_proceso)
    protected $fillable = ['sw_activo','sw_pasivo'];
    protected $searchable = ['search'];

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where('sw_activo','like', $constraint->getValue())
                    ->orWhere('observacion','like', $constraint->getValue());
            return true;
        }

        // default logic should be executed otherwise
        return false;
    }
}
