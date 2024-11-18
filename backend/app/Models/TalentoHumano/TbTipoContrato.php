<?php

namespace App\Models\TalentoHumano;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class TbTipoContrato extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $table = 'tb_tipo_contrato';
    protected $primaryKey = 'tipo_contrato';
    protected $fillable = ['descripcion','duracion'];
    public $timestamps = false;

    protected $searchable = ['search'];

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where('descripcion',$constraint->getOperator(),$constraint->getValue())
                ->orWhere('duracion',$constraint->getOperator(),$constraint->getValue())
                ->orWhere('tipo_contrato',$constraint->getOperator(),$constraint->getValue());

            return true;
        }

        // default logic should be executed otherwise
        return false;
    }
}
