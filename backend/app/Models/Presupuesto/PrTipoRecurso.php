<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;
use Jedrzej\Searchable\Constraint;

class PrTipoRecurso extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = [
        'codigo',
        'nombre',
        'situacion_recurso'
    ];

    public $searchable = ['search'];

    public function getSearchableAttributes()
    {
        return ['search'];
    }
    protected function processSearchFilter(Builder $builder, Constraint $constraint) {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function($query) use ($constraint){
                $query->where('codigo',$constraint->getOperator(),$constraint->getValue())
                    ->orWhere('nombre', $constraint->getOperator(), $constraint->getValue());
            });
            return true;
        }
        return false;
    }
}