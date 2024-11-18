<?php


namespace App\Http\Controllers\CuentasMedicas;


use App\Models\CuentasMedicas\CmConcurrencia;
use App\Models\RedServicios\RsCie10;
use App\Models\RedServicios\RsEntidade;

trait RelacionesConcurrenciasTrait
{
    public function concurrencia(){
        return $this->belongsTo(CmConcurrencia::class, 'cm_concurrencia_id');
    }

    public function diagnostico_relacionado(){
        return $this->belongsTo(RsCie10::class, 'dx_relacionado');
    }

}