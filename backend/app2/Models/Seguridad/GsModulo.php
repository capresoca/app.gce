<?php

namespace App\Seguridad;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class GsModulo extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public function componentes () {
        return $this->hasMany(GsComponente::class,'gs_modulo_id');
    }

//    public function scopeBuscar($query, $data)
//    {
//        return $query
//            ->whereHas('afiliado',function ($query) use ($data){
//                $query->whereHas('tercero',function ($query) use ($data){
//                    $query->where('identificacion', 'like', '%'.$data . '%')
//                        ->orWhere('apellido1', 'like', '%'. $data . '%')
//                        ->orWhere('apellido2', 'like', '%'. $data . '%')
//                        ->orWhere('nombre1', 'like', '%' . $data . '%')
//                        ->orWhere('nombre2', 'like', '%'. $data . '%');
//                });
//            });
//
//    }
}
