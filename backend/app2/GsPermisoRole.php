<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GsPermisoRole extends Model
{
    //
    public function GsComponente(){
        return $this->belongsTo(GsComponente::class, 'gs_componente_id');
    }
    public function GsRoles(){
        return $this->belongsTo('App\GsRole', 'gs_role_id');
    }
}
