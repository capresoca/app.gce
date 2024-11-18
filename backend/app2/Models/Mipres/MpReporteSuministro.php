<?php

namespace App\Models\Mipres;

use Illuminate\Database\Eloquent\Model;

class MpReporteSuministro extends Model
{
    protected $fillable =[
        'suministro_id',
        'UltEntrega',
        'suministro_id',
        'EntregaCompleta',
        'CausaNoEntrega',
        'NoPrescripcionAsociada',
        'ConTecAsociada',
        'CantTotEntregada',
        'NoLote',
        'ValorEntregado',
        'reportado'
    ];

    public function causa()
    {
        return $this->belongsTo(MpCausasnoentrega::class,'CausaNoEntrega');
    }

    public function prescripcion()
    {
        return $this->belongsTo(MpPrescripcione::class,'NoPrescripcionAsociada','NoPrescripcion');
    }
}
