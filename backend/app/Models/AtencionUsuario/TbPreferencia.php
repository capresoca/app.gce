<?php

namespace App\Models\AtencionUsuario;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;

class TbPreferencia extends Model
{
    use PimpableTrait;

    protected $table = 'tb_preferencia';
    protected $primaryKey = 'consecutivo_preferencia';
    protected $fillable = ['descripcion','tipo_preferencia','valor','valor_texto'];
    public $timestamps = false;
    protected $searchable = ['search'];

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where('consecutivo_preferencia','like', $constraint->getValue());
            return true;
        }

        // default logic should be executed otherwise
        return false;
    }
}
