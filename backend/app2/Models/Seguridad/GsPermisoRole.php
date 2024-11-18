<?php

namespace App\Seguridad;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class GsPermisoRole extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'gs_role_id', 'gs_componente_id', 'confirmar', 'anular', 'agregar', 'imprimir', 'ver'
    ];
    protected $hidden = ['created_at', 'updated_at'];

    public function GsComponente(){
        return $this->belongsTo(GsComponente::class, 'gs_componente_id');
    }
    public function GsRoles(){
        return $this->belongsTo(GsRole::class, 'gs_role_id','id');
    }
}
