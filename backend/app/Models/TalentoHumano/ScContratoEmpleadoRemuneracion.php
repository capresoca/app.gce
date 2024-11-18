<?php

namespace App\Models\TalentoHumano;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @author Jorge Hernández 01/05/2020
 * Creando Soluciones Informaticas Ltda
 */

class ScContratoEmpleadoRemuneracion extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $table = 'sc_contrato_empleado_remuneracion';
    protected $primaryKey = 'contrato_empleado_remuneracion';
    protected $fillable = [
        'contrato_empleado',
        'salario_base',
        'observacion'
    ];
    public $timestamps = false;
    protected $searchable = ['search'];

    public function contrato () {
        return $this->belongsTo(ScContratoEmpleado::class,'contrato_empleado');
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint) {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where('observacion', $constraint->getOperator(),$constraint->getValue());
            return true;
        }
        return false;
    }
}
