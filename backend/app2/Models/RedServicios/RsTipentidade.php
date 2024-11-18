<?php

namespace App\Models\RedServicios;

use App\Models\Riips\RsEntidadTemporale;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class RsTipentidade extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public function RsEntidadTemporale () {
        return $this->hasMany(RsEntidadTemporale::class);
    }

}
