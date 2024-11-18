<?php

namespace App\Models\Cartera;

use App\Models\Niif\NfConrtf;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Niif\NfNiif;
use App\Models\Niif\GnTercero;
use App\Models\Niif\NfCencosto;
use App\Models\Cartera\CrCuentasxcobrar;
use App\Models\Cartera\CrConcepto;
use OwenIt\Auditing\Contracts\Auditable;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;

class CrConceptoCuentasxcobrar extends Model implements Auditable
{
    use PimpableTrait;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'id',
        'gn_tercero_id',
        'nf_cencosto_id',
        'nf_conrtf_id',
        'nf_niif_id',
        'cr_cuentaxcobrar_id',
        'cr_concepto_id',
        'valor'
    ];
    protected $searchable = ['search'];

    public function niif(){
        return $this->belongsTo(NfNiif::class,'nf_niif_id');
    }
    public function tercero(){
        return $this->belongsTo(GnTercero::class,'gn_tercero_id');
    }
    public function cencosto(){
        return $this->belongsTo(NfCencosto::class,'nf_cencosto_id');
    }
    public function cuentaPorCobrar(){
        return $this->belongsTo(CrCuentasxcobrar::class,'cr_cuentaxcobrar_id');
    }
    public function concepto(){
        return $this->belongsTo(CrConcepto::class,'cr_concepto_id');
    }
    public function conrtf () {
        return $this->belongsTo(NfConrtf::class,'nf_conrtf_id');
    }

//    protected function processSearchFilter(Builder $builder, Constraint $constraint) {
//        // this logic should happen for LIKE/EQUAL operators only
//        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
//            $builder->where(function($query) use ($constraint){
//            	$query->where('nombre',$constraint->getOperator(),$constraint->getValue())
//                        ->orWhere('codigo', $constraint->getOperator(), $constraint->getValue());
//            });
//            return true;
//        }
//        return false;
//    }
}
