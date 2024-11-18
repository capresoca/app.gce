<?php

namespace App\Models\TalentoHumano;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;

class ThGruposEmpleado extends Model implements Auditable
{
    use PimpableTrait;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'codigo',
        'nombre',
        'perido',
        'retirados',
        'ultima_nomina',
        'liquidar_nomina',
        'proxima_nomina',
        'evaluar_meses',
        'maneja_provisiones',
        'bloqueo_grupos'
    ];
    protected $searchable = ['search'];
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
