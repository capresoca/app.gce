<?php

namespace App\Models\AuditorCuentas;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class AcAuditore extends Model implements Auditable {
	use PimpableTrait;
	use \OwenIt\Auditing\Auditable;

	protected $fillable = [
		'codigo',
		'gs_usuario_id',
		'tipo',
		'estado',
		'auditor_concurrente',
        'tecnico'
	];

	protected $searchable = ['search','estado','tipo','tecnico','auditor_concurrente'];
	protected $appends = ['identificacion_completa'];

	public function usuario() {
		return $this->belongsTo(User::class, 'gs_usuario_id');
	}

	public function getIdentificacionCompletaAttribute () {
	    $id = $this->attributes['gs_usuario_id'];
	    $usuario = User::find($id);
	    $concatenacion = 'CC ' . $usuario->identification;
	    return $concatenacion;
    }

	protected function processSearchFilter(Builder $builder, Constraint $constraint) {
		// this logic should happen for LIKE/EQUAL operators only
		if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
			$builder->where(function ($query) use ($constraint) {
				$query->where('codigo', $constraint->getOperator(), $constraint->getValue());
			})->orWhere(function ($query) use ($constraint) {
				$query->whereHas('usuario', function ($query) use ($constraint) {
					$query->where('name', $constraint->getOperator(), $constraint->getValue())
						->orWhere('identification', $constraint->getOperator(), $constraint->getValue())
						->orWhere('email', $constraint->getOperator(), $constraint->getValue());
				});
			});
			return true;
		}
		return false;
	}

	protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        AcAuditore::creating(function ($table) {
            $val = (integer) AcAuditore::where('tipo','=',$table->tipo)->count();
            $table->codigo = ($table->tipo === 'Médico' ? 'M' : 'A') . ($val + 1);
        });

    }
}