<?php

namespace App\Models\Autorizaciones;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class Refcup extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected  $table = 'refcups';
    protected $primaryKey = 'codigo';
    public $incrementing = false;

    protected $fillable = [
        'codigo',
        'descrip',
        'genero',
        'ambito',
        'lInf',
        'valor',
        'lSup',
        'estancia',
        'unico',
        'nivel',
        'AT',
        'copago',
        'pos',
        'altoCosto',
        'cober',
        'rep',
        'esp',
        'Qx',
        'aut',
        'freq',
        'ind',
        'fi',
        'ff'
    ];

    protected $searchable = ['search'];

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function ($query) use ($constraint){
                $query->where('codigo', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('descrip', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('ambito', $constraint->getOperator(), $constraint->getValue());
            });

            return true;
        }

        // default logic should be executed otherwise
        return false;
    }
}
