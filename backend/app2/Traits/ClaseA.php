<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 9/10/2018
 * Time: 3:13 PM
 */

namespace App\Traits;


use App\Models\Aseguramiento\AsTipbduaarchivo;
use function GuzzleHttp\Psr7\str;

abstract class ClaseA
{
    public static function dividir($a,$b){
        try{
            $c = $a/$b;
            return $c;
        }
        catch (\Exception $e){
            throw $e;
        }
    }

    /**
     * @param $nombreArchivo
     * @return AsTipbduaarchivo|mixed
     * @throws \Exception
     */
    public static function getTipoArchivo($nombreArchivo){
        $tipos = AsTipbduaarchivo::orderBy('sufijo','desc')->orderBy('iniciales','desc')->get();


        foreach ($tipos as $tipo) {
            $caracteres_prefijo = strlen($tipo->iniciales);

            $caracteres_sufijo = strlen($tipo->sufijo);

            if( !$caracteres_prefijo && !$caracteres_prefijo ) continue;

            $presunto_prefijo = !$caracteres_prefijo ? null : substr($nombreArchivo,0,$caracteres_prefijo);

            if($presunto_prefijo != $tipo->iniciales) continue;

            $presunto_sufijo = !$caracteres_sufijo ? null: static::getSufijoArchivoDBUA($nombreArchivo, $caracteres_sufijo);

            if($presunto_sufijo != $tipo->sufijo) continue;

            return $tipo;

        }

        throw new \Exception('Archivo desconocido');

    }

    private static function getSufijoArchivoDBUA($nombreArchivo,$caracteres)
    {
        $lastThreeLetter = substr($nombreArchivo, -4);

        if(static::esExtensionConocida($lastThreeLetter)){
            $nombreArchivo = static::getSufijoArchivoDBUA(substr($nombreArchivo, 0,-4), $caracteres);
        }

        return substr($nombreArchivo,-1*$caracteres);
    }

    private static function esExtensionConocida($ext)
    {
        $ext = strtoupper($ext);
        return $ext === '.ZIP' || $ext === '.TXT';
    }
}