<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class GsPermisoRole extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public function GsComponente(){
        return $this->belongsTo(GsComponente::class, 'gs_componente_id');
    }
    public function GsRoles(){
        return $this->belongsTo('App\GsRole', 'gs_role_id');
    }
}