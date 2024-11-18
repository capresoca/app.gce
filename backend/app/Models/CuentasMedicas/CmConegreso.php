<?php

namespace App\Models\CuentasMedicas;

use App\Http\Controllers\CuentasMedicas\RelacionesConcurrenciasTrait;
use App\Models\RedServicios\RsCie10;
use App\Models\RedServicios\RsEntidade;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CmConegreso extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, RelacionesConcurrenciasTrait;

    protected $fillable = [
        'cm_concurrencia_id',
        'fecha_egreso',
        'estado_salida',
        'rs_entidad_destino_id',
        'dx_egreso',
        'dx_relacionado',
    ];


    public function diagnostico_egreso(){
        return $this->belongsTo(RsCie10::class, 'dx_egreso');
    }
    public function concurrencia()
    {
        return $this->belongsTo(CmConcurrencia::class, 'cm_concurrencia_id');
    }
    public function diagnostico_relacionado(){
        return $this->belongsTo(RsCie10::class, 'dx_relacionado');
    }

    public static function allRelations()
    {
        return [
            'diagnostico_egreso',
            'diagnostico_relacionado'
        ];
    }

}
