<?php

namespace App\Models\ContratacionEstatal;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CeSegbienservicio extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'codigo', 'nombre', 'estado'
    ];
    protected $appends = ['label'];
    protected $hidden = ['created_at', 'updated_at'];

    public function familias () {
        return $this->hasMany(CeFambienservicio::class, 'ce_segbienservicio_id');
    }

    public function children () {
        return $this->hasMany(CeFambienservicio::class, 'ce_segbienservicio_id');
    }

    public function getLabelAttribute () {
        return $this->nombre;
    }
}
