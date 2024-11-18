<?php

namespace App\Models\AtencionUsuario;

use Illuminate\Database\Eloquent\Model;

class AuMotivosgenerale extends Model
{
    protected $appends = ['label'];

    public function children()
    {
        return $this->hasMany(AuMotivo::class, 'au_motivogeneral_id');
    }

    public function getLabelAttribute () {
        return $this->descripcion;
    }

    public function macromotivo()
    {
        return $this->belongsTo(AuMacromotivo::class, 'au_macromotivo_id');
    }
}
