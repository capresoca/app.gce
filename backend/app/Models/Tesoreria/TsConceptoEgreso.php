<?php

namespace App\Models\Tesoreria;

use App\Models\Niif\NfNiif;
use App\Models\Presupuesto\PrEntidadResolucione;
use App\Models\Presupuesto\PrRubro;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class TsConceptoEgreso extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    use PimpableTrait;

    protected $searchable = ['search'];

    protected $fillable = [
        'codigo',
        'nombre',
        'nf_niif_id',
        'naturaleza',
        'created_at',
        'updated_at',
        'facturacion',
        'anticipos',
        'descuentos',
        'presupuesto',
        'acreedores',
        'concepto_cajamenor',
        'orden_pago',
        'reembolso_cajamenor',
        'descuento_cajamenor',
        'cdt',
        'devolucion_paciente',
        'bancos',
        'maneja_contrato',
        'maneja_incapacidad',
        'maneja_cuentamedica',
        'pr_detstrgasto_id',
        'ts_cuenta_id',
        'ts_caja_id',
        'pr_detstringreso_id',
        'pr_entidad_resolucion_ing_id',
        'pr_entidad_resolucion_egr_id'
    ];

    public function cuenta_banco(){
        return $this->belongsTo(TsCuenta::class, 'ts_cuenta_id');
    }
    public function niif(){
        return $this->belongsTo(NfNiif::class, 'nf_niif_id');
    }
    public function presupuestal(){
        return $this->belongsTo(PrRubro::class, 'pr_rubro_presupuestal_id');
    }
    public function ingreso(){
        return $this->belongsTo(PrRubro::class, 'pr_rubro_ingreso_id');
    }

    public function entidadResolucionIng()
    {
        return $this->belongsTo(PrEntidadResolucione::class,'pr_entidad_resolucion_ing_id');
    }

    public function entidadResolucionEgr()
    {
        return $this->belongsTo(PrEntidadResolucione::class,'pr_entidad_resolucion_egr_id');
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


