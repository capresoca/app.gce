<?php

namespace App\Models\RedServicios;

use App\Models\Aseguramiento\AsAfiliado;
use Illuminate\Database\Eloquent\Model;

class RsServcontratado extends Model
{

    protected $appends = [];
    protected $fillable = [
        'porcentaje_afiliados',
        'rs_servicio_id',
        'rs_contrato_id',
        'porcentaje_contratado'
    ];

    public function servicio()
    {
        return $this->belongsTo(RsServicio::class, 'rs_servicio_id');
    }

    public function contrato()
    {
        return $this->belongsTo(RsPlanescobertura::class, 'rs_contrato_id');
    }

    public function afiliados()
    {
        return $this->belongsToMany(AsAfiliado::class,'rs_afiliado_servicios','rs_servcontratado_id','as_afiliado_id');
    }

    public function afiliadosAsignados()
    {
        return $this->afiliados()->count();
    }

    public function getCupoAttribute()
    {
        return $this->porcentajeAsignado < $this->porcentaje_afiliados;
    }

    public function getPorcentajeAsignadoAttribute()
    {
        return ($this->afiliadosAsignados() / AsAfiliado::contarActivos($this->municipioServicio()))*100;
    }

    public function municipioServicio()
    {
        if(!$this->contrato->entidad->gn_municipiosede_id) return null;

        return $this->contrato->entidad->gn_municipio_id;
    }

}
