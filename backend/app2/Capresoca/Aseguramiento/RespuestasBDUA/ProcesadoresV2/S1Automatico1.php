<?php
/**
 * Created by PhpStorm.
 * User: Wilfredo
 * Date: 5/02/2020
 * Time: 2:41 PM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadoresV2;


use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorV3;
use App\Models\Aseguramiento\Procesos\AsS1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class S1Automatico1 extends ProcesadorV3
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
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

        $tabla_s1Automatico_temporal = $this->cargarATablaTemporal('as_s1autvals');

        $respuesta = $this->cargarATabla('as_s1autvals');

        $this->createIndiceIdentificacion($tabla_s1Automatico_temporal);

        $this->validarAfiliadoExiste($tabla_s1Automatico_temporal);

        $this->afiliadoFallecido($tabla_s1Automatico_temporal);

        if(count($this->respuesta)){
            DB::statement("DROP TABLE {$tabla_s1Automatico_temporal}");
            DB::table('as_s1autvals')->where('as_svid_id','=',$respuesta->id)->delete();
            $respuesta->delete();
            return;
        }

        $this->notificarActivos($tabla_s1Automatico_temporal);

        $this->notificarActivar($tabla_s1Automatico_temporal);

        $this->updateAfiliadoYTramite($tabla_s1Automatico_temporal);

        DB::statement("DROP TABLE IF EXISTS {$tabla_s1Automatico_temporal}");

    }

    private function notificarActivos(string $tabla_s1Automatico_temporal): void
    {
        $afiliadosActivados = DB::select("
                SELECT c.id, c.tipo_identificacion, c.identificacion,c.nombre_completo FROM
                as_tramites AS a
                LEFT JOIN as_traslatramites AS b ON b.as_tramite_id=a.id
                LEFT JOIN as_afiliados AS c ON c.id = b.as_afiliado_id
                LEFT JOIN `{$tabla_s1Automatico_temporal}` AS d ON d.tipoIdentificacion=c.tipo_identificacion AND d.identificacion=c.identificacion
                WHERE a.tipo_tramite = 'Traslado Subsidiado' AND d.codeps IS NOT NULL AND c.estado='3'
            ");

        array_push($this->respuesta,[
            'tipo' => 'error',
            'titulo' => 'Afiliados que ya se encuentran activos',
            'afiliados' => $afiliadosActivados
        ]);
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

        array_push($this->respuesta,[
            'tipo' => 'error',
            'titulo' => 'Afiliados activados',
            'afiliados' => $afiliadosActivar
        ]);
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