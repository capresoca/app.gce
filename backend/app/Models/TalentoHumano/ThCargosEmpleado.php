<?php

namespace App\Models\TalentoHumano;

use Illuminate\Database\Eloquent\Model;
use App\Models\TalentoHumano\ThNivelesCargo;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;

class ThCargosEmpleado extends Model implements Auditable
{
    use PimpableTrait;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'codigo',
        'nombre',
        'th_niveles_cargo_id',
        'horas_extra',
        'recargos',
        'cargos_aprobados',
        'tope_maximo'
    ];
    protected $searchable = ['search'];
    public function nivel(){
        return $this->belongsTo(ThNivelesCargo::class,'th_niveles_cargo_id');
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
