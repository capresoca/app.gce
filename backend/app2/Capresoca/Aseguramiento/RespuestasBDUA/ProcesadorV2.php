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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

abstract class ProcesadorV2
{
    protected $csvPath;
    protected $proceso;
    protected $campos;

    public function __construct($csvPath, AsBduaproceso $proceso = null)
    {
        $this->csvPath = $csvPath;
        $this->proceso = $proceso;
    }

    protected function cargarATabla($tablaBase)
    {
        Log::info($this->campos);
        $file = storage_path('app/' . $this->csvPath);
        $file = str_replace('\\', '/', $file);
        $nombre_tabla = $tablaBase . Str::random(16);

        DB::statement("SET GLOBAL local_infile = `ON`");
        DB::statement("CREATE TABLE {$nombre_tabla} SELECT * FROM {$tablaBase} LIMIT 0");

        DB::connection()->getPdo()
            ->exec("
            LOAD DATA LOCAL INFILE '{$file}'
            INTO TABLE {$nombre_tabla}
            FIELDS TERMINATED BY ','
            LINES TERMINATED BY '\r\n'
            IGNORE 0 LINES
            ({$this->campos})");

        DB::statement("SET GLOBAL local_infile = `OFF`");

        return $nombre_tabla;
    }

    /**
     * @param string $tabla_temporal
     */
    protected function createIndiceIdentificacion(string $tabla_temporal): void
    {
        DB::statement("ALTER TABLE {$tabla_temporal} ADD PRIMARY KEY (tipoIdentificacion, identificacion)");
    }

    protected function validarDuplicados(string $tabla_temporal)
    {
        $afiliadosDuplicados = DB::select("
            SELECT concat(a.tipoIdentificacion,'-',a.identificacion,' - Error: Registro duplicado por tipo y numero de identificacion') as descri
            FROM {$tabla_temporal} AS a
            GROUP BY tipoIdentificacion,identificacion
            HAVING     count(*) > 1
        ");

        foreach ($afiliadosDuplicados as $afiliadosActivo) {
            $this->pushMonitor($afiliadosActivo->descri, 'info--text');
        }
    }

    protected function validarAfiliadoExiste($tabla_temporal)
    {
        $afiliadosInexistentes = DB::select("
                    SELECT concat(a.tipoIdentificacion,'-',a.identificacion,' - Error: Afiliado no existe en la BD') as descri
                    FROM {$tabla_temporal} AS a
                    GROUP BY tipoIdentificacion,identificacion
                    HAVING     count(*) > 1
        ");

        foreach ($afiliadosInexistentes as $afiliadosInexistente) {
            $this->pushMonitor($afiliadosInexistente->descri, 'error--text');
        }

    }

    protected function afiliadoFallecido($tabla_temporal)
    {
        $fallecidos = DB::select("
            SELECT concat(a.tipoIdentificacion,'-',a.identificacion,' - Error: Afiliado fallecido') as descri
              FROM {$tabla_temporal} AS a
              LEFT JOIN as_afiliados b ON b.tipo_identificacion = a.tipoIdentificacion AND b.identificacion = a.identificacion
              WHERE b.estado = 8
        "
        );

        foreach ($fallecidos as $fallecido) {
            $this->pushMonitor($fallecido->descri, 'error-text');
        }
    }


    protected function pushMonitor($message, $class)
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
                $this->proceso
            )
        );
    }

    protected function pushAvance(AsBduarespuesta $respuesta)
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
                $this->proceso
            )
        );
    }
}