<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class PrDetrp extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;
    protected $table = 'pr_detrps';
    protected $fillable = [
        'fecha_ven',
        'pr_rp_id',
        'pr_detcdp_id',
        'pr_tipo_gasto_id',
        'pr_rubro_id',
        'valor',
        'valor_debito',
        'valor_credito',
        'valor_ejecutado'
    ];

    protected $appends = ['valor_disponible'];

    public function rubro () {
        return $this->belongsTo(PrConcepto::class, 'pr_rubro_id');
    }

    public function detalle_cdp () {
        return $this->belongsTo(PrDetcdp::class, 'pr_detcdp_id');
    }

    public function tipoGasto ()
    {
        return $this->belongsTo(PrTiposGasto::class, 'pr_tipo_gasto_id');
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
