<?php

namespace App\Models\Temporales;

use Illuminate\Database\Eloquent\Model;

class TempMsubsidiado extends Model
{

    public function scopeNuevos($query)
    {
        return $query->leftJoin('as_afiliados', function ($join){
            $join->on('temp_msubsidiados.tipo_identificacion','=','as_afiliados.tipo_identificacion')
                 ->on('temp_msubsidiados.numero_identificacion','=','as_afiliados.identificacion');
        });
    }

    public function scopeSubsidiado($query)
    {

    }
}
