<?php

namespace App\Models\ContratacionEstatal;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class CeTipoPoliza extends Model implements Auditable {
	use PimpableTrait;
	use \OwenIt\Auditing\Auditable;

	protected $fillable = ['codigo', 'nombre'];

	protected $searchable = ['search'];
	protected function processSearchFilter(Builder $builder, Constraint $constraint) {
		// this logic should happen for LIKE/EQUAL operators only
		if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
			$builder->where(function ($query) use ($constraint) {
				$query->where('codigo', $constraint->getOperator(), $constraint->getValue())
					->orWhere('nombre', $constraint->getOperator(), $constraint->getValue());
			});
			return true;
		}
		return false;
	}
}
