<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 12/09/2018
 * Time: 11:20 AM
 */

namespace App\Capresoca\Aseguramiento\Glosas;


use App\Capresoca\Aseguramiento\Retramite;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsBduaglosa;
use App\Models\Aseguramiento\AsBduaregrespuesta;

abstract class ProcesadorGlosaBase
{
    protected $afiliado;
    protected $regRespuesta;
    protected $glosa;
    protected $columnas;

    public function __construct(AsBduaglosa $glosa, AsAfiliado $afiliado, AsBduaregrespuesta $regRespuesta)
    {
        $this->afiliado = $afiliado;
        $this->regRespuesta = $regRespuesta;
        $this->glosa = $glosa;
        $this->columnas = $this->glosaToArray($glosa->glosa);
    }

    protected function glosaToArray(String $glosa)
    {
        $subsCodGlosa = substr($glosa, 7);
        $dataGlosa = substr($subsCodGlosa, 0,-1);
        return explode('|',$dataGlosa);
    }

    protected function crearRetramite(){
        if(!$this->regRespuesta->nuevo_tramite) {
            $retramite = new Retramite($this->regRespuesta->tramite);
            $nuevoTramite = $retramite->getNuevoTramite();
            $this->regRespuesta->nuevo_tramite_id = $nuevoTramite->id;
            $this->regRespuesta->save();
        }
    }

    protected function corregida(){
        $this->glosa->estado = 'Corregida';
        $this->glosa->as_nuevotramite_id = $this->regRespuesta->nuevo_tramite_id;
        $this->glosa->save();
    }

}