<?php


namespace App\Capresoca\CuentasMedicas;


use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CreateFileRip
{
    public static function runPrefix($rips, $disk, $codHabilitacion)
    {
        $texts = "";
        $sufijo = null;
        $codigoRadicadion = null;
        $prefixs = array();
        foreach ($rips as $key => $ruta) {
            $codigoRadicadion = explode('/', $ruta)[1];
            $name = File::name($ruta);
            $prefijo = trim(substr($name, 0, 2));
            $sufijo = trim(substr($name, 2, 100));

            array_push($prefixs, $prefijo);
        }

        $existsPrefix = in_array('CT', $prefixs);

        if (!in_array('CT', $prefixs)) {
            foreach ($rips as $key => $ruta) {
                $archivo = $disk->get($ruta);
                $lineas = explode(PHP_EOL, $archivo);
                $cantidadLineas = count($lineas);
                $name = File::name($ruta);
                $cantidad = explode(PHP_EOL,$texts);
                $texts = (!empty($texts) ? $texts : '') . $codHabilitacion.','.Carbon::now()->format('d/m/Y').','.$name.','.$cantidadLineas . ((count($cantidad) >= count($prefixs)) ? null : PHP_EOL);
            }

            $fileName = "CT$sufijo.TXT";
            file_put_contents($fileName, $texts);
            $contenido = file_get_contents($fileName);
            $path = 'RIPS/' . $codigoRadicadion .'/archivos/';
            $s3 = Storage::disk('s3');
            $exists = $s3->exists('/RIPS/'.$codigoRadicadion);

            if ($exists) {
                if (is_file($fileName)) {
                    $s3->put("$path/$fileName",$contenido);
                }
            }
        }

        return $existsPrefix;
    }

}