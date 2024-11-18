<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;

class TbVigenciaTraslado extends Model
{
    use PimpableTrait;

    protected $table = 'tb_vigencia_traslado';
    protected $primaryKey = 'consecutivo_vigencia';
    protected $fillable = ['descripcion','fecha_inicio','fecha_fin','tipo','fecha_minima_novedad','fecha_minima_afiliacion','sw_abierto','consecutivo_liquidacion'];
    public $timestamps = false;
    protected $searchable = ['search'];

    public function periodoLiquidacion()
    {
        return $this->belongsTo(TbPeriodoLiquidacion::class,'consecutivo_liquidacion');
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where('consecutivo_vigencia','like', $constraint->getValue());
            if (! is_numeric($constraint->getValue())) {
                $builder->orWhere('descripcion', 'like', '%'.$constraint->getValue().'%');
            }
            return true;
        }

        // default logic should be executed otherwise
        return false;
    }
}
