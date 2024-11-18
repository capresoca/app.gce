<?php

namespace App\Models\ContratacionEstatal;

use App\Models\Niif\GnTercero;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class CeSupervisore extends Model implements Auditable {
	use PimpableTrait;
	use \OwenIt\Auditing\Auditable;

	protected $table = 'ce_supervisores';

	protected $fillable = ['codigo', 'nombre', 'gn_tercero_id'];

	protected $searchable = ['search'];
	public function tercero() {
		return $this->belongsTo(GnTercero::class, 'gn_tercero_id');
	}
	protected function processSearchFilter(Builder $builder, Constraint $constraint) {
		// this logic should happen for LIKE/EQUAL operators only
		if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
			$builder->where(function ($query) use ($constraint) {
				$query->where('codigo', $constraint->getOperator(), $constraint->getValue())
					->orWhere('nombre', $constraint->getOperator(), $constraint->getValue());
			})->orWhere(function ($query) use ($constraint) {
				$query->whereHas('tercero', function ($query) use ($constraint) {
					$query->where('identificacion', $constraint->getOperator(), $constraint->getValue())
						->orWhere('razon_social', $constraint->getOperator(), $constraint->getValue());
				});
			});
			return true;
		}
		return false;
	}
}
