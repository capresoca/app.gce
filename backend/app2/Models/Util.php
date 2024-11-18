<?php
namespace App\Models;

class Util
{
    
    public static function getDescomprimirArchivo($archivoRuta,$rutaDescomprime) {
     
        $zip = new \ZipArchive();
        if ($zip->open($archivoRuta) === TRUE) {
            $zip->extractTo($rutaDescomprime);
            $zip->close();
            //echo 'ok';
            return "";
        } else {
           // echo 'failed';
           return "el archivo no se pudo descomprimir";
        }
        
    }
    
}

