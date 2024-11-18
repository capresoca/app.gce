<?php

namespace App\Models\Mipres;

use Illuminate\Database\Eloquent\Model;

class MpNovetutela extends Model
{
    protected $fillable = [
        'TipoNov',
        'NoPrescripcion',
        'NoPrescripcionF',
        'FNov',
        'mp_tutela_id',
        'mp_tutelafinal_id'
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

