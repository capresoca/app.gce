<?php

namespace App\Models\Validador4505;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use Illuminate\Database\Eloquent\Builder;

class RsPeriodos4505 extends Model implements Auditable
{
    use PimpableTrait;
    use \OwenIt\Auditing\Auditable;

	protected $table = 'rs_periodos4505';

	protected $fillable = [
		'periodo',
		'year',
		'fecha_inicio_validacion',
		'fecha_fin_validacion',
	];

    protected $searchable = ['search'];

    protected function processSearchFilter(Builder $builder, Constraint $constraint) {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function($query) use ($constraint){
            	$query->where('periodo',$constraint->getOperator(),$constraint->getValue())
                        ->orWhere('year', $constraint->getOperator(), $constraint->getValue())
                        ->orWhere('fecha_inicio_validacion', $constraint->getOperator(), $constraint->getValue())
                        ->orWhere('fecha_fin_validacion', $constraint->getOperator(), $constraint->getValue());
            });
            return true;
        }
        return false;
    }
}
