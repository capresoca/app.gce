<?php

namespace App\Models\CuentasMedicas;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CmConhallazgo extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['cm_convisita_id','clase','observaciones'];

    // Relaciones
    public function visita() {
        return $this->belongsTo(CmConvisita::class,'cm_convisita_id');
    }
}
