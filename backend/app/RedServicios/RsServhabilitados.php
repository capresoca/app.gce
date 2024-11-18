<?php

namespace App\RedServicios;

use App\Models\Aseguramiento\AsAfiliado;
use App\Models\RedServicios\RsEntidade;
use App\Models\RedServicios\RsServicio;
use Illuminate\Database\Eloquent\Model;
use App\Models\RedServicios\RsPlanescobertura;

class RsServhabilitados extends Model
{
    protected $fillable = ['rs_entidad_id', 'rs_servicio_id'];

    public function scopeCobertura($query,AsAfiliado $afiliado)
    {
        return $query->whereNotNull('rs_servcontratado_id')->with('entidad');
    }

    public function entidad()
    {
        return $this->belongsTo(RsEntidade::class, 'rs_entidad_id');
    }

    public function servicio()
    {
        return $this->belongsTo(RsServicio::class, 'rs_servicio_id');
    }
    
    public function planes()
    {
        return $this->belongsTo(RsPlanescobertura::class, 'rs_servicio_id');
    }
}
