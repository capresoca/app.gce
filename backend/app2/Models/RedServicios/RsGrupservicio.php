<?php

namespace App\Models\RedServicios;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;

class RsGrupservicio extends Model
{
    use PimpableTrait;

    public function subgrupos()
    {
        return $this->hasMany(RsSubgrupservicio::class, 'rs_grupservicio_id');
    }
}
