<?php

namespace App\Models\AtencionUsuario;

use Illuminate\Database\Eloquent\Model;

class AuTipprestacione extends Model
{
    public function tipos()
    {
        return $this->hasMany(AuTipincapacidade::class, 'au_tipprestacion_id');
    }
}
