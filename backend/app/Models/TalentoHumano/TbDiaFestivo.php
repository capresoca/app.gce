<?php

namespace App\Models\TalentoHumano;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;

class TbDiaFestivo extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $primaryKey = 'dia_festivo';
    protected $fillable = ['descripcion', 'fecha'];
    protected $searchable = ['search'];

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where('descripcion','like', $constraint->getValue());
            return true;
        }

        // default logic should be executed otherwise
        return false;
    }
}
