<?php

namespace App\Models\RedServicios;

use Illuminate\Database\Eloquent\Model;

class RsSubgrupservicio extends Model
{
    public function servicios()
    {
        return $this->hasMany(RsServicio::class, 'rs_subgrupservicio_id');
    }

}
