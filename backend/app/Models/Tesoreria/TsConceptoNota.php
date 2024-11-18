<?php

namespace App\Models\Tesoreria;

use App\Models\Niif\NfNiif;
use App\Models\Presupuesto\PrRubro;
use App\Models\RedServicios\RsEntidade;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use Illuminate\Database\Eloquent\Builder;

class TsConceptoNota extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $searchable = ['search'];

    protected $fillable = ['codigo','nombre','nf_niif_id','naturaleza','cod_retencion','constitucion_cdt','retencion_cdt','acreedores','cxp','ingresos','gastos','rs_entidad_id','pr_rubro_id','recaudo'];

    public function niif(){
        return $this->belongsTo(NfNiif::class, 'nf_niif_id');
    }
    public function rubro(){
        return $this->belongsTo(PrRubro::class, 'pr_rubro_id');
    }
    public function entidad(){
        return $this->belongsTo(RsEntidade::class, 'rs_entidad_id');
    }
    protected function processSearchFilter(Builder $builder, Constraint $constraint) {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function($query) use ($constraint){
              $query->where('nombre',$constraint->getOperator(),$constraint->getValue())
                        ->orWhere('codigo', $constraint->getOperator(), $constraint->getValue())
                        ->orWhere('naturaleza', $constraint->getOperator(), $constraint->getValue());
            })->orWhere(function($query) use ($constraint){
                $query->whereHas('niif',function ($query) use ($constraint) {
                    $query->where('codigo', $constraint->getOperator(), $constraint->getValue())
                        ->orWhere('nombre', $constraint->getOperator(), $constraint->getValue());
                });
            });
            return true;
        }
        return false;
    }
}
