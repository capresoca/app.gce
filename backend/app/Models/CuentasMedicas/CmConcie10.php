<?php

namespace App\Models\CuentasMedicas;

use App\Models\RedServicios\RsCie10;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CmConcie10 extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['cm_convisita_id','dx_relacionado','observaciones','fecha_desde', 'fecha_hasta', 'cm_manglosa_id'];

    // Relaciones
    public function visita() {
        return $this->belongsTo(CmConvisita::class,'cm_convisita_id');
    }
    public function diagnostico_relacionado(){
        return $this->belongsTo(RsCie10::class, 'dx_relacionado');
    }
    public function glosa() {
        return $this->belongsTo(CmManglosa::class, 'cm_manglosa_id');
    }
}
