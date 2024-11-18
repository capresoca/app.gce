<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 13/09/2018
 * Time: 11:02 AM
 */

namespace App\Capresoca\Aseguramiento;

use App\Models\Aseguramiento\AsAfitramite;
use App\Models\Aseguramiento\AsFormtrasmov;
use App\Models\Aseguramiento\AsNovtramite;
use App\Models\Aseguramiento\AsTramite;
use App\Models\Aseguramiento\AsTraslatramite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Retramite
{
    protected $nuevoTramite;

    public function __construct(AsTramite $tramite)
    {
        $tipoTramite = str_replace(' ','',$tramite->tipo_tramite);
        $metodoCrearTramite = 'crear'.$tipoTramite; //Metodo variable
        if(method_exists($this,$metodoCrearTramite))
        {
            $this->$metodoCrearTramite($tramite);
        }else{
            throw new \Exception('No se ah implementado metodo : '.$metodoCrearTramite);
        }
    }

    public function getNuevoTramite()
    {
        return $this->nuevoTramite;
    }

    private function crearAfiliacion($tramiteOriginal)
    {

        $nuevoTramite = $this->crearTramite($tramiteOriginal,'Afiliacion');

        AsAfitramite::create([
            'as_tramite_id' => $nuevoTramite->id,
            'as_afiliado_id' => $tramiteOriginal->afiliacion->as_afiliado_id,
            'as_formafiliacion_id' => $tramiteOriginal->afiliacion->as_formafiliacion_id
        ]);

        $this->nuevoTramite = $nuevoTramite;
    }

    private function crearNovedadSubsidiado($tramiteOriginal)
    {
        $this->crearNovedad($tramiteOriginal);
    }

    private function crearNovedadContributivo($tramiteOriginal)
    {
        $this->crearNovedad($tramiteOriginal);
    }

    private function crearTrasladoContributivo($tramiteOriginal)
    {
        $nuevoTramite = $this->crearTramite($tramiteOriginal,'Traslado Contributivo');


        AsTraslatramite::create(
            [
                'as_tramite_id' => $nuevoTramite->id,
                'as_formtrasmov_id' => $tramiteOriginal->traslado->formulario->id,
                'as_afiliado_id' => $tramiteOriginal->traslado->afiliado->id,
                'as_tipafiliado' => $tramiteOriginal->traslado->as_tipafiliado_id
            ]
        );

        $this->nuevoTramite = $nuevoTramite;

    }
    private function crearTrasladoSubsidiado($tramiteOriginal)
    {
        $nuevoTramite = $this->crearTramite($tramiteOriginal,'Traslado Subsidiado');

        AsTraslatramite::create(
            [
                'as_tramite_id' => $nuevoTramite->id,
                'as_formtrasmov_id' => $tramiteOriginal->traslado->formulario->id,
                'as_afiliado_id' => $tramiteOriginal->traslado->afiliado->id,
                'as_tipafiliado' => $tramiteOriginal->traslado->as_tipafiliado_id,
                'codigo_entidad' => $tramiteOriginal->traslado->codigo_entidad
            ]
        );

        $this->nuevoTramite = $nuevoTramite;

    }


    private function crearNovedad($tramiteOriginal)
    {
        $novedadAutomatica = new NovedadAutomatica($tramiteOriginal->novedad);

        $nuevaNovedad = $novedadAutomatica->getNuevaNovedad();

        if($nuevaNovedad){
            $nuevoTramite = $this->crearTramite($tramiteOriginal, $tramiteOriginal->tipo_tramite);

            $nuevaNovedad['as_tramite_id'] = $tramiteOriginal->id;

            AsNovtramite::create($nuevaNovedad);

            $this->nuevoTramite = $nuevoTramite;
        }
    }

    private function crearTramite($tramiteOriginal, $tipo)
    {
        return AsTramite::create([
            'tipo_tramite' => $tipo,
            'tramite_anterior_id' => $tramiteOriginal->id,
            'consecutivo' => AsTramite::max('consecutivo') + 1,
            'clase_tramite' => 'Automatico',
            'fecha_radicacion' => today()->toDateString(),
            'estado' => 'Radicado'
        ]);
    }
}