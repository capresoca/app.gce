<?php

namespace App\Models\General;

use App\Models\Aseguramiento\AsTramite;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;

class GnBarrio extends Model
{
    use PimpableTrait;

    protected $hidden = ['created_at','updated_at'];

    protected $fillable = ['gn_municipio_id','codigo','nombre'];

    protected $searchable = ['search'];

    public function municipio()
    {
        return $this->belongsTo(GnMunicipio::class,'gn_municipio_id');
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where('nombre','like', $constraint->getValue());
            return true;
        }

        // default logic should be executed otherwise
        return false;
    }


}
