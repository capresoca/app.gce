<?php

namespace App\Models\RedServicios;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class RsCupscategoria extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['rs_cupssubgrupo_id', 'codigo', 'nombre'];

    public function cups () {
        return $this->hasMany(RsCups::class, 'rs_cupscategoria_id');
    }

    public function subgrupo()
    {
        return $this->belongsTo(RsCupssubgrupo::class, 'rs_cupssubgrupo_id');
    }

    public function parent()
    {
        return $this->subgrupo();
    }


}
