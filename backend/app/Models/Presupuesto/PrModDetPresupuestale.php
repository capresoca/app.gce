<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class PrModDetPresupuestale extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;
    protected $table = 'pr_mod_det_presupuestales';

    protected $fillable = [
        'pr_mod_presupuestal_id',
        'pr_detstrgasto_id',
        'pr_rubro_id',
        'pr_tipo_gasto_id',
        'pr_detstringreso_id',
        'pr_tipo_ingreso_id',
        'valor_inicial',
        'naturaleza',
        'valor_movimiento'
    ];

    protected $with = ['tipoGasto','tipoIngreso'];

    public function detalleIngreso () {
        return $this->belongsTo(PrDetstringreso::class,'pr_detstringreso_id');
    }

    public function tipoIngreso ()
    {
        return $this->belongsTo(PrTipoIngreso::class,'pr_tipo_ingreso_id');
    }

    public function detalleGasto () {
        return $this->belongsTo(PrDetstrgasto::class, 'pr_detstrgasto_id');
    }

    public function rubro () {
        return $this->belongsTo(PrConcepto::class, 'pr_rubro_id');
    }

    public function tipoGasto () {
        return $this->belongsTo(PrTiposGasto::class, 'pr_tipo_gasto_id');
    }
}
