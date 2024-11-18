<?php

namespace App\Models\RedServicios;

use App\Models\ContratacionEstatal\CeProconminutaupcedade;
use Illuminate\Database\Eloquent\Model;

class RsRanupc extends Model
{
    public function upcedades()
    {
        return $this->hasMany(CeProconminutaupcedade::class);
    }
}
