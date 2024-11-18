<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 6/11/2018
 * Time: 9:27 AM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores;


use App\Capresoca\Aseguramiento\RespuestasBDUA\Procesador;
use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorCSVInterface;
use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorV2;
use App\Events\BduaProcesosEvent;
use App\Models\Aseguramiento\AsBduaarchivo;
use App\Models\Aseguramiento\AsBduaproceso;
use App\Models\Aseguramiento\AsBduaregrespuesta;
use App\Models\Aseguramiento\AsTramite;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsPobespeciale;
use App\Models\Aseguramiento\AsPagadore;
use App\Models\General\GnTipdocidentidade;
use App\Models\Aseguramiento\AsTipafiliado;
use App\Models\Aseguramiento\AsParentesco;
use App\Models\Aseguramiento\AsCondicionDiscapacidade;
use App\Models\Aseguramiento\AsAfiliadoPagador;
use App\Models\Aseguramiento\AsBduarespuesta;
use App\Models\General\GnMunicipio;
use App\Models\Niif\NfCiiu;
use App\Models\General\GnZona;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Traits\ToolsTrait;
use App\Models\General\GnSexo;
use App\Capresoca\Aseguramiento\RespuestasBDUA\S1Trait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class S1Automatico extends ProcesadorV2
{

    protected $csvPath;
    protected $proceso;
    use S1Trait;

    public function __construct($csvPath, AsBduaproceso $proceso = null)
    {
        parent::__construct($csvPath, $proceso);
        $this->campos = "codeps,
        tipoIdentificacion,
        identificacion,
        primerApellido,
        segundoApellido,
        primerNombre,
        segundoNombre,
        fechaNacimiento,
        genero,
        tipoIdentificacion1,
        identificacion1,
        primerApellido1,
        segundoApellido1,
        primerNombre1,
        segundoNombre1,
        fechaNacimiento1,
        genero1,
        codigoDepartamento,
        codigoMunicipio,
        zona,
        fechaAfiliacionEps,
        tipoPobla,
        nsisben,
        tipoSubsidio";
    }

    public function procesar()
    {
        try{

            $tabla_s1Automatico_temporal = $this->cargarATabla('as_s1autvals');

            $this->createIndiceIdentificacion($tabla_s1Automatico_temporal);

            $this->validarDuplicados($tabla_s1Automatico_temporal);

            $this->validarAfiliadoExiste($tabla_s1Automatico_temporal);

            $this->afiliadoFallecido($tabla_s1Automatico_temporal);

            $this->notificarActivados($tabla_s1Automatico_temporal);

            $this->notificarActivar($tabla_s1Automatico_temporal);

            $this->updateAfiliadoYTramite($tabla_s1Automatico_temporal);

            DB::statement("DROP TABLE IF EXISTS {$tabla_s1Automatico_temporal}");


        }catch(\Exception $exception){
            $this->pushMonitor('error en procesar s1'. $exception->getMessage(), 'error--text');
            Log::error('error en procesar s1'. $exception->getMessage());
        }

    }


    private function notificarActivados(string $tabla_s1Automatico_temporal): void
    {
        $afiliadosActivados = DB::select("
                SELECT c.tipo_identificacion, c.identificacion,c.nombre_completo FROM
                as_tramites AS a
                LEFT JOIN as_traslatramites AS b ON b.as_tramite_id=a.id
                LEFT JOIN as_afiliados AS c ON c.id = b.as_afiliado_id
                LEFT JOIN `{$tabla_s1Automatico_temporal}` AS d ON d.tipoIdentificacion=c.tipo_identificacion AND d.identificacion=c.identificacion
                WHERE a.tipo_tramite = 'Traslado Subsidiado' AND d.codeps IS NOT NULL AND c.estado='3'

            ");

        $this->pushMonitor("AFILIADOS QUE YA SE ENCUENTRAN ACTIVOS REGIMEN SUBSIDIADO : ". count($afiliadosActivados), 'white--text');

        foreach ($afiliadosActivados as $afiliadosActivo) {
            $mensajeActivo = $afiliadosActivo->tipo_identificacion.' '.$afiliadosActivo->identificacion . ' ' . $afiliadosActivo->nombre_completo;
            $this->pushMonitor($mensajeActivo, 'info--text');
        }
    }


    private function notificarActivar(string $tabla_s1Automatico_temporal): void
    {
        $afiliadosActivar = DB::select("
             SELECT a.id, c.tipo_identificacion, c.identificacion,c.nombre_completo FROM
             (
                SELECT MAX(a.id) AS id, a.tipo_tramite FROM
                 as_tramites AS a
                 LEFT JOIN as_traslatramites AS b ON b.as_tramite_id=a.id
                 LEFT JOIN as_afiliados AS c ON c.id = b.as_afiliado_id
                 LEFT JOIN {$tabla_s1Automatico_temporal} AS d ON d.tipoIdentificacion=c.tipo_identificacion AND d.identificacion=c.identificacion
                 WHERE a.tipo_tramite = 'Traslado Subsidiado' AND d.codeps IS NOT NULL
                 GROUP BY b.as_afiliado_id
             )AS a 
             LEFT JOIN as_traslatramites AS b ON b.as_tramite_id=a.id
             LEFT JOIN as_afiliados AS c ON c.id = b.as_afiliado_id
             LEFT JOIN `{$tabla_s1Automatico_temporal}` AS d ON d.tipoIdentificacion=c.tipo_identificacion AND d.identificacion=c.identificacion
             WHERE a.tipo_tramite = 'Traslado Subsidiado' AND d.codeps IS NOT NULL AND c.estado!='3' and c.estado != 8
        ");


        $this->pushMonitor("AFILIADOS QUE SE ACTIVARON EN EL REGIMEN SUBSIDIADO : ". count($afiliadosActivar), 'white--text');

        foreach ($afiliadosActivar as $afiliadosActivo) {
            $mensajeActivo = $afiliadosActivo->tipo_identificacion.' '.$afiliadosActivo->identificacion . ' ' . $afiliadosActivo->nombre_completo;
            $this->pushMonitor($mensajeActivo, 'info--text');
        }
    }


    private function updateAfiliadoYTramite(string $tabla_s1Automatico_temporal): void
    {
        DB::statement("
             UPDATE
             (
                SELECT MAX(z.id) AS id, z.tipo_tramite FROM
                 as_tramites AS z
                 LEFT JOIN as_traslatramites AS b ON b.as_tramite_id=z.id
                 LEFT JOIN as_afiliados AS c ON c.id = b.as_afiliado_id
                 LEFT JOIN `{$tabla_s1Automatico_temporal}` AS d ON d.tipoIdentificacion=c.tipo_identificacion AND d.identificacion=c.identificacion
                 WHERE z.tipo_tramite = 'Traslado Subsidiado' AND d.codeps IS NOT NULL
                 GROUP BY b.as_afiliado_id
             )AS a
             LEFT JOIN as_tramites AS g ON g.id = a.id 
             LEFT JOIN as_traslatramites AS b ON b.as_tramite_id=a.id
             LEFT JOIN as_afiliados AS c ON c.id = b.as_afiliado_id
             LEFT JOIN `{$tabla_s1Automatico_temporal}` AS d ON d.tipoIdentificacion=c.tipo_identificacion AND d.identificacion=c.identificacion
             SET g.estado = 'Aprobado', c.estado='3', c.as_regimene_id = 2
             WHERE a.tipo_tramite = 'Traslado Subsidiado' AND d.codeps IS NOT NULL AND c.estado!='3' and c.estado != 8
        ");
    }






}