<?php

namespace App\Models\ContratacionEstatal;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class CeFambienservicio extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = [
        'ce_segbienservicio_id', 'codigo', 'nombre', 'estado'
    ];
    protected $appends = ['label'];
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function segbienservicio () {
        return $this->belongsTo(CeSegbienservicio::class, 'ce_segbienservicio_id');
    }

    public function clases () {
        return $this->hasMany(CeClasbienservicio::class, 'ce_fambienservicio_id');
    }

    public function children () {
        return $this->hasMany(CeClasbienservicio::class, 'ce_fambienservicio_id');
    }

    public function getLabelAttribute () {
        return $this->nombre;
    }
}
