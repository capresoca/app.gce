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
use App\Capresoca\Aseguramiento\RespuestasBDUA\S1Trait;
use App\Models\Aseguramiento\AsBduaarchivo;
use App\Models\Aseguramiento\AsBduaproceso;
use App\Models\Aseguramiento\AsBduaregrespuesta;
use App\Models\Aseguramiento\AsTramite;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsClasecotizante;
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
use Illuminate\Support\Facades\Log;
use App\Traits\ToolsTrait;
use App\Models\General\GnSexo;

class R1Automatico extends ProcesadorV2
{
    protected $csvPath;
    protected $proceso;

    public function __construct($csvPath, AsBduaproceso $proceso = null)
    {
        parent::__construct($csvPath, $proceso);
        $this->campos = "eapbC,
            tipoIdentificacion,
            identificacion,
            primerApellido,
            segundoApellido,
            primerNombre,
            segundoNombre,
            fechaNacimiento,
            genero,
            codigoAgente,
            consecutivo,
            tipoTraslado,
            tipoIdentificacionCot,
            identificacionCoti,
            tipoIdentificacion1,
            identificacion1,
            primerApellido1,
            segundoApellido1,
            primerNombre1,
            segundoNombre1,
            fechaNacimiento1,
            genero1,
            tipoCotizante,
            tipoAfiliado,
            parentescoAfiliado,
            condicion,
            codigoDepartamento,
            codigoMunicipio,
            zona,
            fecAfiliacion,
            tipoIdenEmpresa,
            nit,
            codigoActividad";
    }

    public function procesar()
    {
        try {

            $errores = 0;

            $tabla_r1_temp = $this->cargarATabla('r1');

            $this->createIndiceIdentificacion($tabla_r1_temp);

            DB::statement("ALTER TABLE {$tabla_r1_temp} ADD INDEX (nit)");

            $errores += $this->notificarSinAportante($tabla_r1_temp);

            $errores += $this->validaCIIU($tabla_r1_temp);

            $errores += $this->validarInfoAportante($tabla_r1_temp);

            $errores += $this->validarParentesco($tabla_r1_temp);

            if ($errores) {
                throw new \Exception('Favor crear la información faltante');
            }

            $this->validarDuplicados($tabla_r1_temp);

            $this->validarAfiliadoExiste($tabla_r1_temp);

            $this->afiliadoFallecido($tabla_r1_temp);

            $this->noficarParaActivar($tabla_r1_temp);

            $this->updateAfiliadoYTramite($tabla_r1_temp);

        } catch (\Exception $exception) {
            $this->pushMonitor('error en procesar r1' . $exception->getMessage(), 'error--text');
            Log::error('error en procesar r1' . $exception->getMessage());
        }
    }

    private function notificarSinAportante($tabla_r1_temp)
    {
        $sinAportante = DB::select("
             SELECT CONCAT('El aportante no existe NIT:', nit) AS message FROM  {$tabla_r1_temp} a 
             left JOIN as_pagadores ON as_pagadores.identificacion = a.identificacion
                WHERE as_pagadores.identificacion IS null and a.tipoAfiliado = 'C'
                GROUP BY nit
        ");

        foreach ($sinAportante as $registro) {
            $this->pushMonitor($registro->message, 'info--text');
        }

        return count($sinAportante);
    }

    private function validaCIIU($tabla_r1_temp)
    {
        $ciiusInexistentes = DB::select("
             SELECT *
             FROM  {$tabla_r1_temp} a 
             left JOIN nf_ciius ON nf_ciius.codigo = a.codigoActividad
             WHERE nf_ciius.codigo IS NULL AND a.codigoActividad != ''
        ");

        foreach ($ciiusInexistentes as $registro) {
            $this->pushMonitor($registro->message, 'info--text');
        }

        return count($ciiusInexistentes);
    }

    private function validarInfoAportante($tabla_r1_temp)
    {
        $sinInfoAportante = DB::select("
            SELECT * FROM {$tabla_r1_temp} a WHERE a.tipoAfiliado = 'C' 
            AND (a.tipoIdenEmpresa IS NULL OR a.nit IS NULL OR a.codigoActividad IS null)
        ");

        foreach ($sinInfoAportante as $registro) {
            $this->pushMonitor($registro->message, 'info--text');
        }

        return count($sinInfoAportante);

    }

    private function validarParentesco($tabla_r1_temp)
    {
        $sinInfoAportante = DB::select("
            SELECT * FROM {$tabla_r1_temp} a WHERE a.tipoAfiliado = 'B' 
            AND parentescoAfiliado is null
        ");

        foreach ($sinInfoAportante as $registro) {
            $this->pushMonitor($registro->message, 'info--text');
        }

        return count($sinInfoAportante);

    }

    private function noficarParaActivar($tabla_r1_temp)
    {
        $afiliActivar = DB::select(
            "
             SELECT a.id, c.tipo_identificacion, c.identificacion, c.nombre_conpleto FROM
             (
                SELECT MAX(a.id) AS id, a.tipo_tramite FROM
                 as_tramites AS a
                 LEFT JOIN as_traslatramites AS b ON b.as_tramite_id=a.id
                 LEFT JOIN as_afiliados AS c ON c.id = b.as_afiliado_id
                 LEFT JOIN {$tabla_r1_temp} AS d ON d.tipoIdentificacion=c.tipo_identificacion AND d.identificacion=c.identificacion
                 WHERE a.tipo_tramite = 'Traslado Contributivo'
                 GROUP BY b.as_afiliado_id
             )AS a 
             LEFT JOIN as_traslatramites AS b ON b.as_tramite_id=a.id
             LEFT JOIN as_afiliados AS c ON c.id = b.as_afiliado_id
             LEFT JOIN {$tabla_r1_temp} AS d ON d.tipoIdentificacion=c.tipo_identificacion AND d.identificacion=c.identificacion
             WHERE a.tipo_tramite = 'Traslado Contributivo' AND d.eapbC IS NOT NULL AND c.estado!='3' and c.estado != 8
            "
        );


        $this->pushMonitor("AFILIADOS QUE SE ACTIVARON EN EL REGIMEN CONTRIBUTIVO : " . count($afiliActivar), 'white--text');

        foreach ($afiliActivar as $afiliadosActivo) {
            $mensajeActivo = $afiliadosActivo->tipo_identificacion . ' ' . $afiliadosActivo->identificacion . ' ' . $afiliadosActivo->nombre_completo;
            $this->pushMonitor($mensajeActivo, 'info--text');
        }


    }

    private function updateAfiliadoYTramite(string $tabla_r1_temp): void
    {
        DB::statement("
             UPDATE
             (
                SELECT MAX(z.id) AS id, z.tipo_tramite FROM
                 as_tramites AS z
                 LEFT JOIN as_traslatramites AS b ON b.as_tramite_id=z.id
                 LEFT JOIN as_afiliados AS c ON c.id = b.as_afiliado_id
                 LEFT JOIN `{$tabla_r1_temp}` AS d ON d.tipoIdentificacion=c.tipo_identificacion AND d.identificacion=c.identificacion
                 WHERE z.tipo_tramite = 'Traslado Contributivo' AND d.codeps IS NOT NULL
                 GROUP BY b.as_afiliado_id
             )AS a
             LEFT JOIN as_tramites AS g ON g.id = a.id 
             LEFT JOIN as_traslatramites AS b ON b.as_tramite_id=a.id
             LEFT JOIN as_afiliados AS c ON c.id = b.as_afiliado_id
             LEFT JOIN `{$tabla_r1_temp}` AS d ON d.tipoIdentificacion=c.tipo_identificacion AND d.identificacion=c.identificacion
             SET g.estado = 'Aprobado', c.estado='3', c.as_regimene_id = 1
             WHERE a.tipo_tramite = 'Traslado Contributivo' AND d.codeps IS NOT NULL AND c.estado!='3' and c.estado != 8
        ");
    }
}


