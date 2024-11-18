<?php

namespace App\Models\Mipres;

use Illuminate\Database\Eloquent\Model;

class MpNovedade extends Model
{
    protected $fillable = [
        'TipoNov',
        'NoPrescripcion',
        'NoPrescripcionF',
        'mp_prescripcion_id',
        'mp_prescripcionFinal_id',
        'FNov'
    ];

    protected $appends = ['tipo'];

    public function getTipoAttribute()
    {
        if(!$this->TipoNov) return null;

        return [1=> 'Modificación',
                2=> 'Anulación',
                3=> 'Transcripción'][$this->TipoNov];
    }
}







