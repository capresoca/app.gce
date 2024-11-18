<?php

namespace App;

use App\Models\Reportes\Reporte;
use Illuminate\Database\Eloquent\Model;

class GsRole extends Model
{
    public function reportes()
    {
        return $this->belongsToMany(Reporte::class,'re_reproles','gs_role_id','re_reporte_id');
    }
}
