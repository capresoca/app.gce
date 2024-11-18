<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class PrDetcdp extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = [
        'pr_cdp_id',
        'pr_detstrgasto_id',
        'pr_rubro_id',
        'pr_tipo_gasto_id',
        'valor',
        'valor_ejecutado'
    ];

    protected $appends = ['valor_disponible'];

    public function detStrgasto ()
    {
        return $this->belongsTo(PrDetstrgasto::class, 'pr_detstrgasto_id');
    }

    public function rubro () {
        return $this->belongsTo(PrConcepto::class, 'pr_rubro_id');
    }

    public function tipoGasto () {
        return $this->belongsTo(PrTiposGasto::class, 'pr_tipo_gasto_id');
    }

    public function getValorDisponibleAttribute () {

        if (!isset($this->attributes['valor_ejecutado'])) {
            $disponible = (double) $this->valor;
        } else {
            $disponible = ((double) $this->valor) - ((double) $this->attributes['valor_ejecutado']);
        }
        return $disponible;
    }
}
