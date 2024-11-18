<?php

namespace App\Models\RedServicios;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class RsCupsgrupo extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $appends = ['label'];
    protected $fillable = ['codigo', 'nombre'];

    public function children () {
        return $this->hasMany(RsCupssubgrupo::class, 'rs_cupsgrupo_id');
    }

    public function getLabelAttribute () {
        return $this->nombre;
    }
}
