<?php

namespace App\Models\RedServicios;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class RsOtrosentidade extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $searchable = ['codigo','nombre','search'];


    protected $fillable = [
        'rs_entidades_id',
        'codigo',
        'nombre',
        'tarifa',
        'estado'
    ];

    public function entidad ()
    {
        return $this->belongsTo(RsEntidade::class, 'rs_entidades_id');
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only

        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
                $builder->where('codigo', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('nombre', $constraint->getOperator(), $constraint->getValue());
            return true;
        }
        // default logic should be executed otherwise
        return false;
    }


}


