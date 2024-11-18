<?php

namespace App\Seguridad;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class GsRole extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['nombre', 'descripcion'];
    protected $hidden = ['created_at', 'updated_at'];

    public function permisos () {
        return $this->hasMany(GsPermisoRole::class, 'gs_role_id','id');
    }

}
