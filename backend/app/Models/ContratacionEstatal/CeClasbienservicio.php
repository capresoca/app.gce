<?php

namespace App\Models\ContratacionEstatal;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CeClasbienservicio extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    public function fambienservicio () {
        return $this->belongsTo(CeFambienservicio::class, 'ce_fambienservicio_id');
    }

    public function bienservicios () {
        return $this->hasMany(CeBienservicio::class, 'ce_clasbienservicio_id');
    }
}
