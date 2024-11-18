<?php

namespace App\Models\Aseguramiento;

use Illuminate\Database\Eloquent\Model;

class AsTipbduaarchivo extends Model
{
    protected $fillable = [
        'id',
        'iniciales',
        'sufijo',
        'nombre',
        'clase_procesador',
        'tipo_tramite',
        'ejemplo',
        'longitud_nombre',
        'as_regimen_id'

    ];
}
