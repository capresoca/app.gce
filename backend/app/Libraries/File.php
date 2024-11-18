<?php
namespace App\Libraries;

use Illuminate\Support\Facades\Log;

class File {
    
    private $ruta;
    
    public function __construct($ruta) {
        $this->ruta     = $ruta;
    }
    
    public function file() {
        return file($this->ruta);
    }
    
    public function canRead() {
        return is_readable($this->ruta);
    }
    
    public function listFiles($ext=NULL) {
        $archivos       = scandir($this->ruta);
        
        if(empty($ext)) {
            return $archivos;
        }
        $temporal       = array();
        
        foreach ($archivos as $var) {
            $temp       = explode('.',$var);
            if(mb_strtoupper($temp[count($temp)-1])==mb_strtoupper($ext)) {
                $temporal[]     = $var;
            }
        }
        return $temporal;
    }
    
    public function isDirectory() {
        return is_dir($this->ruta);
    }

    /**
     * @param array $data
     * @param array $headers
     * @param string $extension
     * @param string $separator
     * @param string $nombreFile
     *
     * @return false|int|resource
     */
    public static function generarFile (array $data, array $headers, string $extension = '.txt',string $separator = ',', string $nombreFile = 'prueba')
    {
        if (count($data) <= 0) {
            return -1;
        }

        $fileName = "$nombreFile$extension";
        $handle = fopen($fileName,'a+');

        $cabecera = '';
        $cabeceraTot = array();
        if (count($headers) > 0) {
            foreach ($headers as $i => $header) {
                array_push($i);
                $cabecera .= $header.(count($headers) === count($cabeceraTot) ? PHP_EOL : $separator);
            }

            fwrite($handle, $cabecera);
        }

        $body = '';
        $bodyTot = array();
        foreach ($data as $key => $datum) {
            array_push($key);
            if (is_array($data[$key])) {
                foreach ($data[$key] as $itemKey => $datum1) {
                    $body .= $datum1.(count($data) === count($bodyTot) ? PHP_EOL : $separator);
                    fwrite($handle, $body);
                }
            } else {
                $body .= $datum.(count($data) === count($bodyTot) ? PHP_EOL : $separator);
                fwrite($handle, $body);
            }
        }

        fclose($handle);

        return $handle;
    }

}

