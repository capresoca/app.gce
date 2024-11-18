<?php
/**
 * Created by PhpStorm.
 * User: Wilfredo
 * Date: 5/02/2020
 * Time: 6:10 PM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadoresV2;


use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorV3;
use App\Models\Aseguramiento\Procesos\AsR1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class R1Automatico extends ProcesadorV3
{
    public function __construct(Request $request)
    {
        parent::__construct( $request );

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
        $r1AutTemp = $this->cargarATablaTemporal('r1');

        $respuesta = $this->cargarATabla('r1');

        $this->createIndiceIdentificacion($r1AutTemp);

        DB::statement("ALTER TABLE {$r1AutTemp} ADD INDEX (nit)");

        $this->notificarSinAportante($r1AutTemp);

        $this->validaCIIU($r1AutTemp);

        $this->validarInfoAportante($r1AutTemp);

        $this->validarParentesco($r1AutTemp);

        $this->validarAfiliadoExiste($r1AutTemp);

        $this->afiliadoFallecido($r1AutTemp);
        if(count($this->respuesta)){
            DB::statement("DROP TABLE {$r1AutTemp}");
            AsR1::where('as_svid_id', $respuesta->id)->delete();
            $respuesta->delete();
            return;
        };
        $this->noficarParaActivar($r1AutTemp);

        $this->updateAfiliadoYTramite($r1AutTemp);

        DB::statement("DROP TABLE IF EXISTS {$r1AutTemp}");
    }

    private function notificarSinAportante($r1AutTemp)
    {
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''))");

        $sinAportante = DB::select("
             SELECT a.tipoIdentificacion tipo_identificacion, a.identificacion, concat_ws(' ',a.primerNombre,a.segundoNombre,a.primerApellido,a.segundoApellido) nombre_completo FROM  {$r1AutTemp} a 
             left JOIN as_pagadores ON as_pagadores.identificacion = a.identificacion
                WHERE as_pagadores.identificacion IS null and a.tipoAfiliado = 'C'
                GROUP BY nit
        ");

        array_push( $this->respuesta , [
            'tipo' => 'error',
            'titulo' => 'El aportante no existe en la bd',
            'afiliados' => $sinAportante
        ] );
    }

    private function validaCIIU($r1AutTemp)
    {
        $ciiusInexistentes = DB::select("
             SELECT a.tipoIdentificacion, a.identificacion, concat_ws(' ',a.primerNombre,a.segundoNombre,a.primerApellido,a.segundoApellido) nombre_completo
             FROM  {$r1AutTemp} a 
             left JOIN nf_ciius ON nf_ciius.codigo = a.codigoActividad
             WHERE nf_ciius.codigo IS NULL AND a.codigoActividad != '' and a.tipoAfiliado = 'C'
        ");

        array_push( $this->respuesta , [
            'tipo' => 'error',
            'titulo' => 'CIIU invalido o no existente en la BD',
            'afiliados' => $ciiusInexistentes
        ] );
    }

    private function validarInfoAportante($tabla_r1_temp)
    {
        $sinInfoAportante = DB::select("
            SELECT a.tipoIdentificacion, a.identificacion, concat_ws(' ',a.primerNombre,a.segundoNombre,a.primerApellido,a.segundoApellido) nombre_completo 
            FROM {$tabla_r1_temp} a WHERE a.tipoAfiliado = 'C' 
            AND (a.tipoIdenEmpresa IS NULL OR a.nit IS NULL OR a.codigoActividad IS null)
        ");

        array_push( $this->respuesta , [
            'tipo' => 'error',
            'titulo' => 'Registros sin la información del aportante',
            'afiliados' => $sinInfoAportante] );
    }

    private function validarParentesco($r1AutTemp)
    {
        $sinInfoAportante = DB::select("
            SELECT a.tipoIdentificacion, a.identificacion, concat_ws(' ',a.primerNombre,a.segundoNombre,a.primerApellido,a.segundoApellido) nombre_completo
            FROM {$r1AutTemp} a WHERE a.tipoAfiliado = 'B' 
            AND parentescoAfiliado is null
        ");
    }

    protected function validarDuplicados(string $tabla_temporal)
    {
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''))");
        $afiliadosDuplicados = DB::select("
            SELECT null,a.tipoIdentificacion, a.identificacion, concat_ws(' ',a.primerNombre,a.segundoNombre,a.primerApellido,a.segundoApellido) nombre_completo
            FROM {$tabla_temporal} AS a
            GROUP BY tipoIdentificacion,identificacion
            HAVING count(*) > 1
        ");

        array_push($this->respuesta,[
            'tipo' => 'error',
            'titulo' => 'Afiliados que estan duplicados',
            'afiliados' => $afiliadosDuplicados]);
    }


    protected function afiliadoFallecido($tabla_temporal)
    {
        $fallecidos = DB::select("
            SELECT a.tipoIdentificacion, a.identificacion, concat_ws(' ',a.primerNombre,a.segundoNombre,a.primerApellido,a.segundoApellido) nombre_completo
              FROM {$tabla_temporal} AS a
              LEFT JOIN as_afiliados b ON b.tipo_identificacion = a.tipoIdentificacion AND b.identificacion = a.identificacion
              WHERE b.estado = 8
        "
        );

        array_push($this->respuesta,[
            'tipo' => 'error',
            'titulo' => 'Afiliados que no existen en la base de datos',
            'afiliados' => $fallecidos
        ]);
    }

    private function noficarParaActivar($tabla_r1_temp)
    {
        $afiliActivar = DB::select(
            "
             SELECT a.tipoIdentificacion, a.identificacion, concat_ws(' ',a.primerNombre,a.segundoNombre,a.primerApellido,a.segundoApellido) nombre_completo 
             FROM
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

        array_push($this->respuesta,[
            'tipo' => 'info',
            'titulo' => 'Afiliados que no existen en la base de datos',
            'afiliados' => $afiliActivar
        ]);

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

    protected function validarAfiliadoExiste($tabla_temporal)
    {
        $afiliadosInexistentes = DB::select("
                    SELECT a.tipoIdentificacion, a.identificacion, concat_ws(' ',a.primerNombre,a.segundoNombre,a.primerApellido,a.segundoApellido) nombre_completo 
                    FROM {$tabla_temporal} AS a
                    LEFT JOIN as_afiliados  AS b ON b.tipo_identificacion=a.tipoIdentificacion AND b.identificacion=a.identificacion
                    WHERE b.id IS null
        ");

        array_push($this->respuesta,[
            'tipo' => 'error',
            'titulo' => 'Afiliados que no existen en la base de datos',
            'afiliados' => $afiliadosInexistentes
        ]);
    }

}