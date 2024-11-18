<?php

namespace App\Models\CuentasMedicas;

use App\Models\RedServicios\RsCum;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CmConcum extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['cantidad','observaciones','valor','cm_convisita_id','rs_cum_id','causa','fecha','cm_manglosa_id'];

    // Relaciones
    public function visita() {
        return $this->belongsTo(CmConvisita::class, 'cm_convisita_id');
    }
    public function cum() {
        return $this->belongsTo(RsCum::class,'rs_cum_id' );
    }
    public function glosa() {
        return $this->belongsTo(CmManglosa::class, 'cm_manglosa_id');
    }
}
