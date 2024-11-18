<?php

namespace App\Models\RedServicios;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class RsSalminimo extends Model implements Auditable
{
    //
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    public function cuotas_copagos(){
        return $this->hasMany(RsCuotacopago::class, 'rs_salminimo_id');
    }
}
