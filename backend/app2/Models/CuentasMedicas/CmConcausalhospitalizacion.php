<?php

namespace App\Models\CuentasMedicas;

use App\Models\RedServicios\RsCum;
use App\Models\RedServicios\RsCups;
use App\Models\RedServicios\RsServicio;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CmConcausalhospitalizacion extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['cm_convisita_id', 'cm_causalhospitalizacion_id', 'detalle', 'rs_cup_id', 'rs_cum_id', 'rs_servicio_id'];
    protected $appends = [
        'tabla'
    ];

    // Relaciones
    public function visita() {
        return $this->belongsTo(CmConvisita::class, 'cm_convisita_id');
    }
    public function tipo_causal_hospitalizacion() {
        return $this->belongsTo(CmTipocausalhospitalizacion::class, 'cm_causalhospitalizacion_id');
    }
    public  function cup() {
        return $this->belongsTo(RsCups::class,'rs_cup_id' );
    }
    public function cum() {
        return $this->belongsTo(RsCum::class,'rs_cum_id' );
    }
    public function valoracion_especialista(){
        return $this->belongsTo(RsServicio::class, 'rs_servicio_id');
    }

    public function getTablaAttribute()
    {
        return $this->tipo_causal_hospitalizacion->tabla;
    }
}
