<?php

namespace App\Models\AtencionUsuario;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;

class TbRechazoPrestacionSocial extends Model
{
    use PimpableTrait;

    protected $fillable = [
        'descripcion', 'codigo_interno', 'sw_activo'
    ];
    protected $primaryKey = 'consecutivo_rechazo';
    protected $searchable = ['search'];

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where('consecutivo_rechazo','like', $constraint->getValue());
            return true;
        }

        // default logic should be executed otherwise
        return false;
    }
}
