<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class PrDetallesTraslado extends Model implements Auditable
{
    use PimpableTrait;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'naturaleza',
        'pr_detstrgasto_id',
        'pr_detstringreso_id',
        'pr_rubro_id',
        'pr_rubro_trasladado_id',
        'pr_tipo_gasto_id',
        'pr_tipo_ingreso_id',
        'pr_traslado_id',
        'saldo_rubro',
        'valor_rubro_traslado',
        'valor_movimiento',
        'pr_detstrgastodebitado_id',
        'pr_detstringresodebitado_id'
    ];

    protected $with = ['tipoGasto','tipoIngreso'];

    public function detalleIngreso () {
        return $this->belongsTo(PrDetstringreso::class,'pr_detstringreso_id');
    }

    public function detalleIngresoDebitado () {
        return $this->belongsTo(PrDetstringreso::class, 'pr_detstringresodebitado_id');
    }

    public function tipoIngreso ()
    {
        return $this->belongsTo(PrTipoIngreso::class,'pr_tipo_ingreso_id');
    }

    public function detalleGasto () {
        return $this->belongsTo(PrDetstrgasto::class, 'pr_detstrgasto_id');
    }

    public function detalleGastoDebitado ()
    {
        return $this->belongsTo(PrDetstrgasto::class, 'pr_detstrgastodebitado_id');
    }

    public function rubroTraslado () {
        return $this->belongsTo(PrConcepto::class, 'pr_rubro_id');
    }

    public function rubroTrasladado () {
        return $this->belongsTo(PrConcepto::class, 'pr_rubro_trasladado_id');
    }

    public function tipoGasto () {
        return $this->belongsTo(PrTiposGasto::class, 'pr_tipo_gasto_id');
    }
}
