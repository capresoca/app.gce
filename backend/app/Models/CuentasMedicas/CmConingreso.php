<?php

namespace App\Models\CuentasMedicas;

use App\Http\Controllers\CuentasMedicas\RelacionesConcurrenciasTrait;
use App\Models\RedServicios\RsCie10;
use App\Models\RedServicios\RsEntidade;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CmConingreso extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, RelacionesConcurrenciasTrait;

    protected $fillable = ['cm_concurrencia_id', 'fecha_ingreso','via_ingreso','rs_entidad_id', 'dx_ingreso','dx_relacionado','dx_relacionado2'];

    public function diagnostico_relacionado2(){
        return $this->belongsTo(RsCie10::class, 'dx_relacionado2');

    }

    public function diagnostico_ingreso(){
        return $this->belongsTo(RsCie10::class, 'dx_ingreso');
    }

    public function entidad_origen(){
        return $this->belongsTo(RsEntidade::class, 'rs_entidad_id');
    }


}
