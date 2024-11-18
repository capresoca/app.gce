<?php

namespace App\Models\Aseguramiento;

use App\Models\Niif\GnTercero;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class AsPagadore extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    use PimpableTrait;

    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];
    protected $searchable = ['search'];

    public function tercero()
    {
        return $this->belongsTo(GnTercero::class, 'gn_tercero_id');
    }


    public function tipo_aportante()
    {
        return $this->belongsTo(AsTipaportante::class, 'as_tipaportante_id');
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only

        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder
                ->where('razon_social', $constraint->getOperator(), $constraint->getValue())
                ->orWhere('identificacion', $constraint->getOperator(), $constraint->getValue())
                ->orWhereHas('tercero', function ($query) use ($constraint) {
                    $query->where('nombre1', $constraint->getOperator(), $constraint->getValue())
                        ->orWhere('nombre2', $constraint->getOperator(), $constraint->getValue())
                        ->orWhere('apellido1', $constraint->getOperator(), $constraint->getValue())
                        ->orWhere('apellido2', $constraint->getOperator(), $constraint->getValue())
                        ->orWhere('identificacion', $constraint->getOperator(), $constraint->getValue());

                });
            return true;
        }


        // default logic should be executed otherwise
        return false;
    }
}
