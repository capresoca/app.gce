<?php

namespace App\Models\AtencionUsuario;

use Illuminate\Database\Eloquent\Model;

class AuMotivo extends Model
{
    protected $appends = ['label'];

    public function getLabelAttribute () {
        return $this->descripcion;
    }

    public function motivo_general()
    {
        return $this->belongsTo(AuMotivosgenerale::class, 'au_motivogeneral_id');
    }
}
