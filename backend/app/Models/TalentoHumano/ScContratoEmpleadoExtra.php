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

class ScContratoEmpleadoExtra extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $table = 'sc_contrato_empleado_extra';
    protected $primaryKey = 'contrato_empleado_extra';
    protected $fillable = [
        'contrato_empleado',
        'extra_laboral',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'causacion',
        'valor_extra',
        'porcentaje_extra'
    ];
    public $timestamps = false;
    protected $searchable = ['search'];

    public function extralaboral () {
        return $this->belongsTo(TbExtraLaboral::class,'extra_laboral');
    }

    public function contrato () {
        return $this->belongsTo(ScContratoEmpleado::class,'contrato_empleado');
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->whereHas('extralaboral', function ($query) use ($constraint) {
                $query->where('descripcion',$constraint->getOperator(), $constraint->getValue());
            });
            return true;
        }

        // default logic should be executed otherwise
        return false;
    }
}
