<?php

namespace App\Models\Tesoreria;

use App\Models\Niif\NfConrtf;
use App\Models\Niif\NfNiif;
use App\Models\Presupuesto\PrDetstringreso;
use App\Models\Presupuesto\PrEntidadResolucione;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use Illuminate\Database\Eloquent\Builder;

class TsConceptoRecibo extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $searchable = ['search'];
    protected $fillable = ['codigo','nombre','nf_niif_id','naturaleza','facturacion','anticipos','pacientes','cod_retencion'];

    public function niif(){
        return $this->belongsTo(NfNiif::class, 'nf_niif_id');
    }

    public function concepto_retencion()
    {
        return $this->belongsTo(NfConrtf::class,'nf_conrtf_id');
    }

    public function resolucion()
    {
        return $this->belongsTo(PrEntidadResolucione::class,'pr_entidad_resolucion_id');
    }

    public function rubro()
    {
        return $this->belongsTo(PrDetstringreso::class,'pr_detstringreso_id');
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
