<?php

namespace App\Models\AtencionUsuario;

use Illuminate\Database\Eloquent\Model;

class AuMacromotivo extends Model
{
    protected $appends = ['label'];

    public function children()
    {
        return $this->hasMany(AuMotivosgenerale::class, 'au_macromotivo_id');
    }

    public function getLabelAttribute () {
        return $this->descripcion;
    }


}
