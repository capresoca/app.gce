<?php

namespace App\Models\Compensacion;

use App\Models\General\GnArchivo;
use Illuminate\Database\Eloquent\Model;

class CpResultadosCompensacion extends Model
{
    protected $fillable = [
        'archivo_abx_id',
        'archivo_acx_id',
        'fecha_resultado',
        'estado'
    ];

    public function abx()
    {
        return $this->belongsTo(GnArchivo::class, 'archivo_abx_id');
    }

    public function acx()
    {
        return $this->belongsTo(GnArchivo::class,'archivo_acx_id');
    }
}
