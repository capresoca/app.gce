<?php

namespace App\Models\TalentoHumano;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;

class TbFondo extends Model
{
    use PimpableTrait;

    protected $table = 'tb_fondo';
    protected $primaryKey = 'fondo';
    protected $fillable = ['codigo','descripcion','tipo_fondo','digito_verificacion','direccion','clase_fondo','nit','telefono'];
    public $timestamps = false;
    protected $searchable = ['search'];

    public function fondo()
    {
        return $this->belongsTo(TbClaseFondo::class,'clase_fondo');
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where('codigo','like', $constraint->getValue());
            return true;
        }

        // default logic should be executed otherwise
        return false;
    }
}
