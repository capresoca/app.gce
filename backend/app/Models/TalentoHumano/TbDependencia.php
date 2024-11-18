<?php

namespace App\Models\TalentoHumano;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @author Jorge Hernández 27/04/2020
 * Creando Soluciones Informaticas Ltda
 */

class TbDependencia extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $table = 'tb_dependencia';
    protected $primaryKey = 'dependencia';
    protected $fillable = ['descripcion','area'];
    public $timestamps = false;

    protected $searchable = ['search'];

    public function tbarea () {
        return $this->belongsTo(TbArea::class,'area','area');
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->whereHas('tbarea', function ($query) use ($constraint) {
                $query->where('descripcion', $constraint->getOperator(), $constraint->getValue());
            })->orWhere('descripcion',$constraint->getOperator(),$constraint->getValue())
                ->orWhere('dependencia',$constraint->getOperator(),$constraint->getValue());

            return true;
        }

        // default logic should be executed otherwise
        return false;
    }

}
