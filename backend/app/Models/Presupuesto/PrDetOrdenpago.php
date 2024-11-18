<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class PrDetOrdenpago extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = [
        'fecha_ven',
        'pr_orden_de_pago_id',
        'pr_detobligacion_id',
        'pr_obligacion_id',
        'pr_rubro_id',
        'pr_tipo_gasto_id',
        'pr_rp_id',
        'pr_cdp_id',
        'valor',
        'valor_ejecutado'
    ];

    protected $appends = ['valor_disponible'];

    public function tipoGasto ()
    {
        return $this->belongsTo(PrTiposGasto::class, 'pr_tipo_gasto_id');
    }

    public function rubro()
    {
        return $this->belongsTo(PrConcepto::class, 'pr_rubro_id');
    }

    public function detalle_obligacion () {
        return $this->belongsTo(PrDetobligacione::class, 'pr_detobligacion_id');
    }

    public function obligacion () {
        return $this->belongsTo(PrObligacione::class,'pr_obligacion_id');
    }

    public function rp () {
        return $this->belongsTo(PrRp::class, 'pr_rp_id');
    }

    public function cdp ()
    {
        return $this->belongsTo(PrCdp::class, 'pr_cdp_id');
    }

    public function getValorDisponibleAttribute () {
        if (!isset($this->attributes['valor_ejecutado'])) {
            $disponible = $this->valor;
        } else {
            $disponible = $this->valor - ((double) $this->attributes['valor_ejecutado']);
        }
        return $disponible;
    }
}
