<?php

namespace App\Models\OficinaJuridica;

use App\Models\General\GnMunicipio;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class OjJuzgado extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = [
        'codigo',
        'nombre',
        'gn_municipio_id',
        'direccion',
        'telefono',
        'email'
    ];
    protected $hidden = ['created_at', 'updated_at'];
    protected $searchable = ['search','nombre','codigo'];

    public function municipio () {
        return $this->belongsTo(GnMunicipio::class, 'gn_municipio_id');
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where('nombre', $constraint->getOperator(), $constraint->getValue())
                ->orWhere('codigo', $constraint->getOperator(), $constraint->getValue());
            return true;
        }
        // default logic should be executed otherwise
        return false;
    }

}
