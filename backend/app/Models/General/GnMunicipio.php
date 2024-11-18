<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;

class GnMunicipio extends Model
{
    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['nombre_departamento','codigo_planos'];

    public function departamento(){
        return $this->belongsTo(GnDepartamento::class,'gn_departamento_id');
    }

    public function getCodigoPlanosAttribute()
    {
        return substr($this->codigo,2,5);
    }

    public function getNombreDepartamentoAttribute(){
        return $this->departamento->nombre;
    }
}
