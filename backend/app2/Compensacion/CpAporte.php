<?php

namespace App\Compensacion;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class CpAporte extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;
    protected $fillable = [
        'planilla_id',
        'liquidacion_id',
        'dias',
        'retiro',
        'ingreso',
        'fecha_pago',
        'ibc',
        'cotizacion',
        'tipo_cotizante',
        'gs_usuario_id',
        'observacion',
        'novedad',
        'forzar_retiro'
    ];

    public function planilla()
    {
        return $this->belongsTo(CpPlanilla::class, 'planilla_id');
    }

    public function liquidacion()
    {
        return $this->belongsTo(CpLiquidacione::class,'liquidacion_id');
    }

    public function usuario () {
        return $this->belongsTo(User::class,'gs_usuario_id');
    }
}
