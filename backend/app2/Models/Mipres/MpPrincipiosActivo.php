<?php

namespace App\Models\Mipres;

use Illuminate\Database\Eloquent\Model;

class MpPrincipiosActivo extends Model
{
    protected $fillable = [
        'ConOrden',
        'CodPriAct',
        'ConcCant',
        'UMedConc',
        'CantCont',
        'UMedCantCont'
    ];

    protected $with = ['principio_activo','unimedida_concentracion','unimedida_medicamento'];

    public function principio_activo()
    {
        return $this->belongsTo(MpDci::class,'CodPriAct','codigo');
    }

    public function unimedida_concentracion()
    {
        return $this->belongsTo(MpUnimedida::class,'UMedConc','codigo');
    }

    public function unimedida_medicamento()
    {
        return $this->belongsTo(MpUnimedida::class,'UMedCantCont','codigo');
    }
}
