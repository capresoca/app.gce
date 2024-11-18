<?php

namespace App\Models\RedServicios;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class RsCupssubgrupo extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $appends = ['label'];
    protected $fillable = ['rs_cupsgrupo_id', 'codigo', 'nombre'];

    public function children () {
        return $this->hasMany(RsCupscategoria::class,'rs_cupssubgrupo_id');
    }

    public function getLabelAttribute () {
        return $this->nombre;
    }

    public function parent()
    {
        return $this->belongsTo(RsCupsgrupo::class, 'rs_cupsgrupo_id');
    }
}
