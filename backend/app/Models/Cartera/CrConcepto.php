<?php

namespace App\Models\Cartera;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Niif\NfNiif;
use OwenIt\Auditing\Contracts\Auditable;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;

class CrConcepto extends Model implements Auditable
{
    use PimpableTrait;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
		'codigo',
		'nombre',
		'tipo_concepto',
		'nf_niif_id',
		'maneja_tercero',
		'afecta_cartera',
		'afecta_bancos',
		'tipo_aplicacion',
		'glosas',
    ];
    protected $searchable = ['search'];
    protected $hidden = ['created_at','updated_at'];

    public function niif(){
        return $this->belongsTo(NfNiif::class,'nf_niif_id');
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
