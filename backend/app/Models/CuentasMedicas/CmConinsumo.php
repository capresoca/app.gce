<?php

namespace App\Models\CuentasMedicas;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CmConinsumo extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['codigo','descripcion','valor','cantidad','cm_convisita_id','observaciones','fecha','cm_manglosa_id'];

    // Relaciones
    public function visita() {
        return $this->belongsTo(CmConvisita::class,'cm_convisita_id' );
    }
    public function glosa() {
        return $this->belongsTo(CmManglosa::class, 'cm_manglosa_id');
    }
}
