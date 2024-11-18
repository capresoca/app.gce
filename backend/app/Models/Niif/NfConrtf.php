<?php

namespace App\Models\Niif;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Jedrzej\Pimpable\PimpableTrait;
use Illuminate\Database\Eloquent\Builder;
use Jedrzej\Searchable\Constraint;

class NfConrtf extends Model implements Auditable
{
    use PimpableTrait;
    use \OwenIt\Auditing\Auditable;

    protected $appends = ['codigo_nombre'];
    protected $fillable = [
        'codigo', 'nombre', 'manejo', 'base_minima', 'porcentaje', 'estado'
    ];
    protected $hidden = ['created_at', 'updated_at'];
    protected $searchable = ['search'];

    public function detalles(){
        return $this->hasMany(NfConrtfdetalle::class,'nf_conrtf_id');
    }

    public function getCodigoNombreAttribute () {
        return $this->codigo . ' - ' . $this->nombre;
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint) {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function($query) use ($constraint){
              $query->where('nombre',$constraint->getOperator(),$constraint->getValue())
                        ->orWhere('codigo', $constraint->getOperator(), $constraint->getValue());
            });
            return true;
        }
        return false;
    }
}
