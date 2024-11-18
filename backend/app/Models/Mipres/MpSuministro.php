<?php

namespace App\Models\Mipres;

use Illuminate\Database\Eloquent\Model;

class MpSuministro extends Model
{
    protected $fillable = [
        'suministro_id',
        'UltEntrega',
        'EntregaCompleta',
        'CausaNoEntrega',
        'NoPrescripcionAsociada',
        'ConTecAsociada',
        'CantTotEntregada',
        'NoLote',
        'ValorEntregado'
    ];

    public function direccionamiento()
    {
        return $this->belongsTo(MpDireccionamiento::class,'suministro_id','suministro_id');
    }

    public function prescripcion()
    {
        return $this->belongsTo(MpPrescripcione::class, 'NoPrescripcionAsociada','NoPrescripcion');
    }

    public function tutela()
    {
        return $this->belongsTo(MpTutela::class,'NoPrescripcionAsociada','NoTutela');
    }

    public function causaNoEntrega()
    {
        return $this->belongsTo(MpCausasnoentrega::class,'CausaNoEntrega');
    }
}

    
