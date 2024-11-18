<?php

namespace App\Models\RedServicios;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;

class RsCupsentidade extends Model
{
    use PimpableTrait;

    protected $fillable = ['rs_entidad_id','rs_cups_id','tarifa'];
    protected $searchable = ['cup:cm_mansoat_id','cup:cm_maniss_id','search'];
    public function cup()
    {
        return $this->belongsTo(RsCups::class,'rs_cups_id');
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only

        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->whereHas('cup', function ($query) use ($constraint){
                $query->where('descripcion', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('codigo', $constraint->getOperator(), $constraint->getValue());
            });
            return true;
        }
        // default logic should be executed otherwise
        return false;
    }
}
