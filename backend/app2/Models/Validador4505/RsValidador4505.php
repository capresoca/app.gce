<?php

namespace App\Models\Validador4505;

use App\Models\RedServicios\RsEntidade;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class RsValidador4505 extends Model implements Auditable {
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $searchable = ['search'];
    protected $table = 'rs_validador4505';
	public function RsEntidad() {
		return $this->belongsTo(RsEntidade::class, 'rs_entidad_id');
	}

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function ($query) use ($constraint){
                $query->where('cod_radicacion',$constraint->getOperator(), $constraint->getValue())
                    ->orWhereHas('RsEntidad', function ($query) use ($constraint){
                        $query->Where('nombre', $constraint->getOperator(), $constraint->getValue())
                            ->orWhere('cod_habilitacion', $constraint->getOperator(), $constraint->getValue());
                    });

            });
            return true;
        }

        // default logic should be executed otherwise
        return false;
    }
}
