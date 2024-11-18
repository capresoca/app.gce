<?php

namespace App\Models\ContratacionEstatal;

use App\Models\General\GnMunicipio;
use App\Models\Niif\GnTercero;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class CeContratista extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = [
        'ccomercio', 'fecha_ccomercio', 'rep_legal', 'gn_tercero_id', 'gn_munccomercio_id', 'ce_natjuridica_id'
    ];
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    protected $searchable = ['search'];

    public function tercero () {
        return $this->belongsTo(GnTercero::class, 'gn_tercero_id');
    }

    public function muncComercio () {
        return $this->belongsTo(GnMunicipio::class, 'gn_munccomercio_id');
    }

    public function natJuridica () {
        return $this->belongsTo(CeNatjuridica::class, 'ce_natjuridica_id');
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only

        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->whereHas('tercero', function ($query) use ($constraint){
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
