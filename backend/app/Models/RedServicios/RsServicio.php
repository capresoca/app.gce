<?php

namespace App\Models\RedServicios;

use App\Models\Aseguramiento\AsAfiliado;
use App\Models\ContratacionEstatal\CeProconminutaupcservicio;
use App\RedServicios\RsServhabilitados;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class RsServicio extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public function upcservicios()
    {
        return $this->hasMany(CeProconminutaupcservicio::class);
    }

    public function contratados()
    {
        return $this->hasMany(RsServcontratado::class, 'rs_servicio_id');
    }

    public function contratados_portabilidad()
    {
        return $this->hasMany(RsServcontratado::class, 'rs_servicio_id')
            ->whereHas('contrato.contrato', function ($query) {
                $query->wherePortabilidad(1);
            });
    }

    public function habilitados()
    {
        return $this->hasMany(RsServhabilitados::class, 'rs_servicio_id');
    }


    public function scopePrimarios(Builder $query, AsAfiliado $afiliado)
    {
        $servicios_query = $query->wherePrimario(1);

        if ($afiliado->estaPortado()) {
            return $servicios_query->with('contratados_portabilidad.contrato.contrato.entidad');
        }

        if ($afiliado->esContributivo()) {
            return $servicios_query->with([
                'contratados' => function ($query) {
                    $query->whereHas('contrato.contrato', function ($query) {

                        $query->whereContributivo(1)->estaActivo();

                    })->with('contrato.contrato.entidad');
                }
            ]);
        }

        if ($afiliado->esSubsidiado()) {
            return $servicios_query->with([
                'contratados' => function ($query) {
                    $query->whereHas('contrato.contrato', function ($query) {
                        $query->whereSubsidiado(1)->estaActivo();
                    })->with('contrato.contrato.entidad');
                }
            ]);
        }
    }


}



