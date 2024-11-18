<?php

namespace App\Models\Tesoreria;

use App\Models\Pagos\PgAnticipo;
use App\Models\Pagos\PgCxp;
use App\Models\RedServicios\RsPlanescobertura;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class TsConceptoegresoDetalle extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use  PimpableTrait;

    protected $fillable = [
        'ts_compegreso_concepto_id',
        'pg_cxp_id',
        'rs_planescobertura_id',
        'valor'
    ];

    protected $appends = ['contrato'];

    public function cxp()
    {
        return $this->belongsTo(PgCxp::class,'pg_cxp_id');
    }

    public function plan_cobertura () {
        return $this->belongsTo(RsPlanescobertura::class,'rs_planescobertura_id');
    }

    public function anticipo () {
        return $this->hasOne(PgAnticipo::class,'ts_concepoegreso_detalle_id');
    }

    public function getContratoAttribute($key)
    {
        if (isset($this->plan_cobertura)) return $this->plan_cobertura->contrato;
    }
}
