<?php

namespace App\Models\TalentoHumano;

use Illuminate\Database\Eloquent\Model;
use App\Models\Niif\NfCencosto;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;

class ThSubgruposEmpleado extends Model implements Auditable
{
    use PimpableTrait;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'codigo',
        'nombre',
        'nf_cencosto_id'
    ];
    protected $searchable = ['search'];
    public function cencosto(){
        return $this->belongsTo(NfCencosto::class,'nf_cencosto_id');
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
