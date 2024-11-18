<?php

namespace App\Seguridad;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class GsComponente extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public function permisos(){
        return $this->hasMany(GsPermisoRole::class,'gs_componente_id');
    }

    public function GsModulo(){
        return $this->belongsTo(GsModulo::class, 'gs_modulo_id');
    }

    public function getPermisosAttribute($value)
    {
        $permisos = array();
        foreach (explode(',', $value) as $permiso) {
            $permisos[] = (object) array('gs_role_id' => null, 'id' => null, 'propiedad' => $permiso, $permiso => false, 'gs_componente_id' => $this->attributes['id']);
        }
        return $permisos;
    }

}
