<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class    PrDetobligacione extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = [
        'fecha_rp',
        'saldo_por_obligar',
        'valor',
        'valor_ejecutado',
        'valor_debito',
        'valor_credito',
        'pr_obligacion_id',
        'valor_giro',
        'pr_tipo_gasto_id',
        'pr_rubro_id',
        'pr_detrp_id',
        'pr_cdp_id',
        'pr_rp_id'
    ];

    protected $appends = ['valor_disponible'];

    public function tipoGasto()
    {
        return $this->belongsTo(PrTiposGasto::class, 'pr_tipo_gasto_id');
    }

    public function cdp () {
        return $this->belongsTo(PrCdp::class, 'pr_cdp_id');
    }

    public function rp () {
        return $this->belongsTo(PrRp::class,'pr_rp_id');
    }

    public function rubro ()
    {
        return $this->belongsTo(PrConcepto::class, 'pr_rubro_id');
    }

    public function detalle_rp () {
        return $this->belongsTo(PrDetrp::class, 'pr_detrp_id');
    }

//    public function setSaldoPorObligarAttribute ($value) {
//        $this->attributes['saldo_por_obligar'] = $this->attributes['pr_detrp_id'] ? $this->detalle_rp->valor_disponible : 0;
//    }

    public function getValorDisponibleAttribute () {
        if (!isset($this->attributes['valor_ejecutado'])) {
            $disponible = $this->valor;
        } else {
            $disponible = $this->valor - ((double) $this->attributes['valor_ejecutado']);
        }
        return $disponible;
    }

}
