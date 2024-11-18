<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 23/08/2018
 * Time: 4:03 PM
 */

namespace App\Capresoca\Aseguramiento;


use App\Models\Aseguramiento\AsAfitramite;
use App\Models\Aseguramiento\AsFormafiliacione;
use App\Models\Aseguramiento\AsTramite;
use Illuminate\Support\Facades\Auth;

class GeneradorAfitramites
{
    protected $formafiliacion;

    public function __construct(AsFormafiliacione $formafiliacione)
    {
        $this->formafiliacion = $formafiliacione;
        if(!$formafiliacione->recien_nacido){
            $this->crearAfitramite($formafiliacione->afiliado);
        }
        $this->crearTramitesBeneficiarios();

    }

    private function crearTramitesBeneficiarios()
    {
        foreach ($this->formafiliacion->beneficiarios as $beneficiario) {
            $this->crearAfitramite($beneficiario);
        }
    }

    private function crearAfitramite($afiliado)
    {

        $tramite = AsTramite::create([
            'tipo_tramite' => $afiliado->as_regimene_id === 2 ? 'Afiliacion' : 'MC',
            'clase_tramite' => 'Manual',
            'fecha_radicacion' => $this->formafiliacion->fecha_radicacion,
            'estado' => 'Radicado',
            'gs_usuradica_id' => Auth::user()->id,
            'gs_usuario_id' => Auth::user()->id
        ]);

        AsAfitramite::create([
            'as_tramite_id' => $tramite->id,
            'as_afiliado_id' => $afiliado->id,
            'as_formafiliacion_id' => $this->formafiliacion->id
        ]);
    }

}