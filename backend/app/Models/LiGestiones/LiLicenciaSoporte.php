<?php

namespace App\Models\LiGestiones;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use App\Models\LiGestiones\LiLicencia;

class LiLicenciaSoporte extends Model
{
    protected $primaryKey = 'consecutivo_soporte';
    protected $fillable = [ 'consecutivo_licencia', 'url', 'soporte' ];

    protected $searchable = ['search'];

    public function licencia (){
        return $this->belongsTo(LiLicencia::class, 'consecutivo_licencia');
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where('primer_apellido','like', $constraint->getValue());
            return true;
        }

        // default logic should be executed otherwise
        return false;
    }
}
