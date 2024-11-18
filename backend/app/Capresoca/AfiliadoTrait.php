<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 19/01/2019
 * Time: 10:19 AM
 */

namespace App\Capresoca;


use App\Models\Aseguramiento\AsAfiliado;
use App\Models\General\GnTipdocidentidade;
use Illuminate\Support\Facades\Log;

trait AfiliadoTrait
{
    /**
     * @param String $tipoIdentificacion
     * @param String $identificacion
     * @return mixed
     * @throws \Exception
     */
    protected function getAfiliado(String $tipoIdentificacion, String $identificacion) : AsAfiliado
    {
        if($tipoIdentificacion === 'NV'){
            $tipoIdentificacion = 'CN';
        }

        $tipoId = GnTipdocidentidade::where('abreviatura', $tipoIdentificacion)->first();

        if(!$tipoId)
            throw new \Exception('No se pudo encontrar el tipo de identificacion: '. $tipoIdentificacion. ' Identificacion: '.$identificacion);

        $identificacion = [
            'identificacion' => $identificacion,
            'tipoId' => $tipoId->id
        ];

        $afiliado = AsAfiliado::AfiliadoPorIdentificacion($identificacion)->first();

        if(!$afiliado)
            throw new \Exception('No se encontro el afiliado: ' . $tipoIdentificacion . ' ' . $identificacion['identificacion']);

        return $afiliado;
    }
    protected function getTraslateTipoIdentificacionAportante(String $tipoIdentificacion){
        if ($tipoIdentificacion === 'NI') {
            $tipoIdentificacion = 'NIT';
        }else{
            if($tipoIdentificacion === 'NV'){
                $tipoIdentificacion = 'CN';
            }
        }
        return $tipoIdentificacion;
    }
}