<?php

namespace App\Models\RedServicios;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class RsPlancobertura extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    public function detalles()
    {
        return $this->belongsTo(RsDetPlancobertura::class, 'rs_plancobertura_id');
    }
}
