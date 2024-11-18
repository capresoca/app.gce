<?php

namespace App\Models\TalentoHumano;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;

class TbClaseFondo extends Model
{
    use PimpableTrait;

    protected $table = 'tb_clase_fondo';
    protected $primaryKey = 'clase_fondo';
    protected $fillable = ['descripcion','tipo_fondo'];
    public $timestamps = false;
    protected $searchable = ['search'];

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where('clase_fondo','like', $constraint->getValue());
            return true;
        }

        // default logic should be executed otherwise
        return false;
    }
}
