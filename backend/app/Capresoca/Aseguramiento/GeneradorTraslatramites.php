<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24/09/2018
 * Time: 9:08 AM
 */

namespace App\Capresoca\Aseguramiento;


use App\Models\Aseguramiento\AsFormtrasmov;
use App\Models\Aseguramiento\AsTramite;
use App\Models\Aseguramiento\AsTraslatramite;
use App\Models\General\GnEmpresa;
use Illuminate\Support\Facades\Auth;

class GeneradorTraslatramites
{
    protected $formtrasmov;

    public function __construct(AsFormtrasmov $formtrasmov)
    {
        $this->formtrasmov = $formtrasmov;
        $this->empresa = GnEmpresa::first();
        if(!$formtrasmov->recien_nacido){
            $this->crearTraslatramite($formtrasmov->afiliado,false);
        }
        $this->crearTramitesBeneficiarios();

    }

    private function crearTramitesBeneficiarios()
    {
        foreach ($this->formtrasmov->beneficiarios as $beneficiario) {
            $this->crearTraslatramite($beneficiario, true);
        }
    }

    private function crearTraslatramite($afiliado, $es_beneficiario)
    {
        $tramite = AsTramite::create([
            'tipo_tramite' => $this->getTipoTramite($afiliado),
            'clase_tramite' => 'Manual',
            'fecha_radicacion' => $this->formtrasmov->fecha_radicacion,
            'estado' => 'Radicado',
            'gs_usuradica_id' => Auth::user() ? Auth::user()->id : null,
            'gs_usuario_id' => Auth::user() ? Auth::user()->id : null
        ]);

        $traslatramite = AsTraslatramite::create([
            'as_tramite_id' => $tramite->id,
            'as_afiliado_id' => $afiliado->id,
            'as_formtrasmov_id' => $this->formtrasmov->id,
            'codigo_entidad' => $this->getCodigoEntidad($afiliado),
            'as_tipafiliado_id' => $es_beneficiario ? 3 : 1
        ]);

    }

    private function getTipoTramite($afiliado){
        $nuevoRegimen = $this->formtrasmov->afiliado->as_regimene_id;

        if($this->formtrasmov->tipo === 'Movilidad'){
            $nuevoRegimen = $nuevoRegimen === 1 ? 2 : 1;

        }

        $tipoTramite = $nuevoRegimen === 2 ? 'Traslado Subsidiado' : 'Traslado Contributivo';
        return $tipoTramite;
    }

    private function getCodigoEntidad($afiliado)
    {

        if($afiliado->as_regimene_id === 1){
            return $this->empresa->codhabilitacion_contrib;
        }

        return $this->empresa->codhabilitacion_subsid;

    }

}