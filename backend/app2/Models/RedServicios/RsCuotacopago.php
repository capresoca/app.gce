<?php

namespace App\Models\RedServicios;

use Illuminate\Database\Eloquent\Model;

class RsCuotacopago extends Model
{
    public function salminimo()
    {
        return $this->belongsTo(RsSalminimo::class, 'rs_salminimo_id');
    }
}
