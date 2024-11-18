<?php

namespace App\Models\RedServicios;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class RsCumcontratados extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = ['rs_contratos_id','rs_cum_id','tarifa','codigo','descripcion','nombre','tarifa_entidad','tipo_valor'];

    protected $searchable = ['search','rs_contratos_id'];

    public function plan_cobertura ()
    {
        return $this->belongsTo(RsPlanescobertura::class,'rs_contratos_id');
    }

    public function cum()
    {
        return $this->belongsTo(RsCum::class, 'rs_cum_id');
    }

    public function getDescripcionAttribute(){
        return $this->cum->producto. '. '.$this->cum->descripcion_comercial;
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only

        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {

            $builder->whereHas('cum', function ($query) use ($constraint){
                $query->where('producto', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('descripcion_comercial', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('expediente', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('expediente_cum', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('titular', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('registro_sanitario', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('principio_activo', $constraint->getOperator(), $constraint->getValue());
            });
            return true;
        }
        // default logic should be executed otherwise
        return false;
    }
}
