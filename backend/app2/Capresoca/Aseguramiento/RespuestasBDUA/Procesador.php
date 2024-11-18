<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 31/08/2018
 * Time: 6:12 PM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA;


use App\Capresoca\AfiliadoTrait;
use App\Capresoca\LeerCsv;
use App\Events\BduaProcesosEvent;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsBduaarchivo;
use App\Models\Aseguramiento\AsBduapendiente;
use App\Models\Aseguramiento\AsBduaproceso;
use App\Models\Aseguramiento\AsBduarespuesta;
use App\Models\General\GnArchivo;
use App\Models\General\GnTipdocidentidade;
use App\Models\Niif\GnTercero;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

abstract class Procesador
{
    use LeerCsv;
    use AfiliadoTrait;

    protected $data;
    protected $csvPath;
    protected $proceso;
    protected $fileName;
    protected $reader;
    protected $numFilas;
    protected $procesadas;
    protected $aplicadas;
    protected $respuesta;

    public function __construct($csvPath, AsBduaproceso $proceso = null)
    {
        try {
            $this->csvPath = $csvPath;
            $this->reader = $this->leerFromUrl($csvPath);
            $this->numFilas = count($this->reader);
            $this->data = $this->reader->getRecords();
            $this->proceso = $proceso;
            $this->procesadas = 0;
            $this->aplicadas = 0;
            $this->fileName = $this->getFileName($csvPath);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

    }


    protected function crearGnArchivo()
    {
        return GnArchivo::create(
            [
                'ruta' => $this->csvPath,
                'size' => Storage::size($this->csvPath),
                'nombre' => $this->fileName
            ]
        );
    }

    private function getFileName($path)
    {
        $fileName = explode('/', $path);
        return array_last($fileName);
    }

    protected function getTercero(String $tipoIdentificacion, String $identificacion)
    {
        $tipoId = GnTipdocidentidade::where('abreviatura', $tipoIdentificacion)->first();

        return GnTercero::where(
            [
                ['gn_tipdocidentidad_id', '=', $tipoId->id],
                ['identificacion', '=', $identificacion],
            ]
        )->first();

    }

    protected function crearRespuesta($tipoRespuesta)
    {
        $nombreArchivo = substr($this->fileName, 0, 16);
        $bduaArchivo = AsBduaarchivo::where('nombre', $nombreArchivo)->first();

        if (!$bduaArchivo)
            return false;

        $gnArchivo = $this->crearGnArchivo();

        return AsBduarespuesta::create([
            'as_bduaarchivo_id' => $bduaArchivo->id,
            'tipo_respuesta' => $tipoRespuesta,
            'gn_archivo_id' => $gnArchivo->id,
            'total_registros' => $this->numFilas,
            'avance' => $this->procesadas,
            'aplicadas' => $this->aplicadas
        ]);
    }

    protected function pushRespuesta(AsBduarespuesta $respuesta, AsBduaproceso $proceso)
    {
        $log = 'Se inicio el procesamiento de la respuesta ' . $respuesta->tipo_respuesta . ': ' . $respuesta->archivo->nombre;
        $this->pushMonitor($log, $proceso, 'info');

        broadcast(
            new BduaProcesosEvent(
                [
                    "type" => "respuesta",
                    "as_bdaarchivo_id" => $respuesta->archivo->id,
                    "respuesta" => $respuesta
                ],
                $proceso
            )
        );
    }

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

    protected function pushAvance(AsBduaproceso $bduaproceso, AsBduarespuesta $respuesta)
    {
        $respuesta->avance = $this->procesadas;
        $respuesta->aplicadas = $this->aplicadas;
        $respuesta->save();

        broadcast(
            new BduaProcesosEvent(
                [
                    "type" => "avance",
                    "id" => $respuesta->id,
                    "as_bdaarchivo_id" => $respuesta->archivo->id,
                    "respuesta" => $respuesta
                ],
                $bduaproceso
            )
        );
    }

    protected function guardarPendiente($motivo)
    {
        try {

            AsBduapendiente::create([
                'as_bduarespuesta_id' => $this->respuesta->id,
                'motivo' => $motivo
            ]);

        } catch (\Exception $e) {
            Log::error($e);
        }
    }

    protected function getRegimenFromFileName()
    {
        $eps = substr($this->fileName, 2, 6);

        if ($eps === 'EPS025') return 2;

        if ($eps === 'EPSC25') return 1;

        throw new \Exception('EPS invalida');
    }

    protected function getFechaProcesoFromFileName()
    {
        $year = substr($this->fileName, 12, 4);
        $month = substr($this->fileName, 10, 2);
        $day = substr($this->fileName, 8, 2);

        return $year . '-' . $month . '-' . $day;
    }

}