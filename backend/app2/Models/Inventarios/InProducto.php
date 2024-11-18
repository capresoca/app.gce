<?php

namespace App\Models\Inventarios;

use App\Models\Inventarios\InGrupo;
use App\Models\Inventarios\InSubgrupo;
use App\Models\Inventarios\InUnidadesMedida;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class InProducto extends Model implements Auditable {
	use PimpableTrait;
	use \OwenIt\Auditing\Auditable;

	protected $fillable = [
		'codigo',
		'descripcion',
		'in_grupo_id',
		'in_subgrupo_id',
		'in_unidad_medida_id',
		'costo_producto',
		'codigo_iva',
		'fecha_creacion',
		'estado',
	];
	protected $searchable = ['search'];
	public function grupo() {
		return $this->belongsTo(InGrupo::class, 'in_grupo_id');
	}
	public function subgrupo() {
		return $this->belongsTo(InSubgrupo::class, 'in_subgrupo_id');
	}
	public function unidadmedida() {
		return $this->belongsTo(InUnidadesMedida::class, 'in_unidad_medida_id');
	}
	protected function processSearchFilter(Builder $builder, Constraint $constraint) {
		// this logic should happen for LIKE/EQUAL operators only
		if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
			$builder->where(function ($query) use ($constraint) {
				$query->where('codigo', $constraint->getOperator(), $constraint->getValue())
					->orWhere('descripcion', $constraint->getOperator(), $constraint->getValue());
			});
			return true;
		}
		return false;
	}
}
