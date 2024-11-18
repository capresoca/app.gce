<?php

namespace App\Models\Mipres;

use Illuminate\Database\Eloquent\Model;

class MpEntrega extends Model
{
    protected $fillable = [
        'mp_medicamento_id',
        'mp_nutricional_id',
        'mp_procedimiento_id',
        'mp_complementario_id',
        'mp_dispositivo_id',
        'fecha_aprobada',
        'fecha_entrega',
        'cantidad_aprobada',
        'cantidad_entregada',
        'estado',
    ];
}



