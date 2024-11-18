<?php

namespace App\Models\RedServicios;

use App\Models\RerServicios\RsTipcapinstalada;
use Illuminate\Database\Eloquent\Model;

class RsCapinstalada extends Model
{
    protected $fillable = ['rs_entidades_id','rs_tipcapinstalada_id','cantidad'];

    public function tipo()
    {
        return $this->belongsTo(RsTipcapinstalada::class, 'rs_tipcapinstalada_id');
    }
}
