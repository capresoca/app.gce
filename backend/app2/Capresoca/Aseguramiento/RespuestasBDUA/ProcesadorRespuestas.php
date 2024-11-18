<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 3/09/2018
 * Time: 11:28 AM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA;


use App\Events\BduaProcesosEvent;
use App\Jobs\ProcesarArchivoBDUA;
use App\Models\Aseguramiento\AsBduaarchivo;
use App\Models\Aseguramiento\AsBduaproceso;
use App\Models\Aseguramiento\AsTipbduaarchivo;
use App\Models\General\GnArchivo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class ProcesadorRespuestas
{
    protected $files;
    protected $mensajes;
    protected $proceso;
    protected $directorio_proceso;
    protected $parametros;

    public function __construct($files, AsBduaproceso $proceso)
    {
        $this->files = $files;
        $this->proceso = $proceso;
        $this->directorio_proceso = 'Aseguramiento/RespuestasBDUA/' . $this->proceso->tipo . $this->proceso->id . $this->proceso->uuid; //Cambiar ID Por ID
        $this->mensajes = collect();
    }

    public function procesarArchivos()
    {
        Log::info('procesar archivos 41');
        foreach ($this->files as $file) {
            $this->subirArchivosStorage($file);
        }

        $this->procesarCSVFromStorage();

        return $this->mensajes;
    }

    private function subirArchivosStorage($file)
    {
        Log::info('procesar archivos 54');
        $mime = $file->getClientMimeType();
        if ($mime === 'application/zip') {
            Storage::disk('local')->extractTo($this->directorio_proceso, $file->path());
            return true;
        }

        if ($mime === 'text/plain') {
            Log::info($file->getClientOriginalName());
            Storage::disk('local')->putFileAs($this->directorio_proceso, $file, $file->getClientOriginalName());
            return true;
        }

        $this->pushMonitor('No es un archivo valido: ' . $file->getClientOriginalName(), $this->proceso, 'error--text');

        return false;
    }

    private function procesarCSVFromStorage()
    {
        Log::info('procesar archivos 74');
        $paths = Storage::disk('local')->files($this->directorio_proceso);
        foreach ($paths as $path) {
            try {
                Log::info('Path: ' . $path);
                $nombreArchivo = $this->getFileName($path); //Se toma de la ruta puesto que no se puede acceder a getOriginalName de los archivo descomprimidos.

                $tipo_archivo = $this->getTipoArchivo($nombreArchivo);
                if ($tipo_archivo) {
                    if ($tipo_archivo->clase_procesador) {
                        if (GnArchivo::where('ruta', $path)->first()) {
                            $log = 'Este archivo ya fue procesado: ' . $nombreArchivo;
                            $this->pushMonitor($log, $this->proceso, 'error--text');
                            continue;
                        }
                        if ($this->existsBduaarchivo($nombreArchivo)) {
                            $log = 'Este bdua archivo ya fue procesado: ' . $nombreArchivo;
                            $this->pushMonitor($log, $this->proceso, 'error--text');
                            continue;
                        }

                        $log = 'Se inició el procesamiento del archivo: ' . $nombreArchivo;
                        $this->pushMonitor($log, $this->proceso, 'white--text');

//                        ProcesarArchivoBDUA::dispatch($tipo_archivo,$path,$this->proceso)->onQueue('archivos');
                        ProcesarArchivoBDUA::dispatchNow($tipo_archivo, $path, $this->proceso);
                    } else {
                        $log = 'No se ha implementado un algoritmo para el tipo de archivo: ' . $tipo_archivo->iniciales . '.' . $tipo_archivo->sufijo;

                        $this->pushMonitor($log, $this->proceso, 'white--text');

                        Storage::delete($path);
                        continue;
                    }
                } else {
                    Storage::delete($path);
                    $log = 'Este tipo de archivo es desconocido para el sistema: ' . $nombreArchivo;
                    $this->pushMonitor($log, $this->proceso, 'error--text');
                    continue;

                }
            } catch (\Exception $e) {
                $this->pushMonitor($e->getMessage(), $this->proceso, 'error--text');
                continue;
            }
        }

    }


    private function getSufijoArchivoBDUA($nombreArchivo, $caracteres)
    {

        $lastThreeLetter = substr($nombreArchivo, -4);
        if ($this->esExtensionConocida($lastThreeLetter)) {
            $nombreArchivo = $this->getSufijoArchivoBDUA(substr($nombreArchivo, 0, -4), $caracteres);
        }
        return substr($nombreArchivo, -1 * $caracteres);
    }


    /**
     * @param $ext
     * @return bool
     */
    private function esExtensionConocida($ext)
    {
        $ext = strtoupper($ext);
        return $ext === '.ZIP' || $ext === '.TXT';
    }

    /**
     * @param $nombreArchivo
     * @return AsTipbduaarchivo|mixed
     * @throws \Exception
     */
    private function getTipoArchivo($nombreArchivo)
    {
        $tipos = AsTipbduaarchivo::orderBy('sufijo', 'DESC')->get();

        foreach ($tipos as $tipo) {
            $caracteres_prefijo = strlen($tipo->iniciales);
            $caracteres_sufijo = strlen($tipo->sufijo);

            if (!$caracteres_prefijo && !$caracteres_prefijo) continue;

            $presunto_prefijo = !$caracteres_prefijo ? null : substr($nombreArchivo, 0, $caracteres_prefijo);

            if ($presunto_prefijo != $tipo->iniciales) continue;

            $presunto_sufijo = !$caracteres_sufijo ? null : $this->getSufijoArchivoBDUA($nombreArchivo, $caracteres_sufijo);

            if ($presunto_sufijo != $tipo->sufijo) continue;

            return $tipo;

        }

        throw new \Exception('Tipo de Archivo desconocido');

    }

    /**
     * @param $path
     * @return mixed
     */
    private function getFileName($path)
    {
        $fileName = explode('/', $path);
        return array_last($fileName);
    }

    /**
     * @param $message
     * @param AsBduaproceso $proceso
     * @param $class
     */
    protected function pushMonitor($message, AsBduaproceso $proceso, $class)
    {
        Log::info($message);
        broadcast(
            new BduaProcesosEvent(
                [
                    "type" => "monitor",
                    "message" => [
                        'text' => $message,
                        'class' => $class
                    ]
                ],
                $proceso
            )
        );
    }

    /**
     * @param $nombreArchivo
     * @return mixed
     */
    private function existsBduaarchivo($nombreArchivo)
    {
        $nombre_bdua = explode('.', $nombreArchivo);

        $nombre_bdua = array_first($nombre_bdua);

        $bdua_archivo = AsBduaarchivo::where('nombre', $nombre_bdua)->first();

        return $bdua_archivo;

    }


}