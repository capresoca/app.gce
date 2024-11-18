<?php

namespace App\Models\CuentasMedicas;

use App\Models\RedServicios\RsCie10;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CmConaltocosto extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['cm_concurrencia_id', 'tipo','rs_cie10_id'];

    public function concurrencia(){
        return $this->belongsTo(CmConcurrencia::class, 'cm_concurrencia_id');
    }

    public function diagnostico(){
        return $this->belongsTo(RsCie10::class, 'rs_cie10_id');
    }
}
