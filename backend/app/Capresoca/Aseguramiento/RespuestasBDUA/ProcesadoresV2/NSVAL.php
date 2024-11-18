<?php
/**
 * Created by PhpStorm.
 * User: Wilfredo
 * Date: 5/02/2020
 * Time: 4:33 PM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadoresV2;


use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorV3;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NSVAL extends ProcesadorV3
{
    public function __construct(Request $request)
    {
        parent::__construct( $request );
        $this->campos = "consecutivo,
                        codigoEntidad,
                        tipoIdentificacion,
                        identificacion,
                        primerApellido,
                        segundoApellido,
                        primerNombre,
                        segundoNombre,
                        fechaNacimiento,
                        codigoDepartamento,
                        codigoMunicipio,
                        codigoNovedad,
                        fechaInicio,
                        nuevoDato,
                        nuevoDato1,
                        nuevoDato2,
                        nuevoDato3,
                        nuevoDato4,
                        nuevoDato5,
                        nuevoDato6";
    }

    public function procesar()
    {
        $this->cargarATabla('ns_nc_val');

        $tablaTemporal = $this->cargarATablaTemporal('ns_nc_val');

        $this->respuestaValidados( $tablaTemporal );

        $this->actualizarEstadoTramite( $tablaTemporal );
        DB::statement( "DROP TABLE IF EXISTS {$tablaTemporal}" );
    }

    /**
     * @param $tablaTemporal
     */
    private function actualizarEstadoTramite($tablaTemporal): void
    {
        DB::statement( "UPDATE 
                {$tablaTemporal} AS a
                LEFT JOIN as_afiliados AS b ON b.tipo_identificacion = a.tipoIdentificacion AND b.identificacion = a.identificacion
                LEFT JOIN as_novtramites AS c ON c.as_afiliado_id = b.id
                LEFT JOIN as_tramites AS d ON d.id = c.as_tramite_id
                LEFT JOIN as_tipnovedades AS e ON d.id = c.as_tipnovedade_id
                SET d.estado='Procesado'
                WHERE c.id IS NOT NULL AND d.estado = 'Radicado' AND b.as_regimene_id = 2 AND c.as_tipnovedade_id=2" );
    }

    /**
     * @param $tablaTemporal
     */
    private function respuestaValidados($tablaTemporal): void
    {
        $validados = DB::SELECT( "SELECT  b.id, b.tipo_identificacion, b.identificacion,b.nombre_completo FROM
                {$tablaTemporal} AS a
                LEFT JOIN as_afiliados AS b ON b.tipo_identificacion = a.tipoIdentificacion AND b.identificacion = a.identificacion
                LEFT JOIN as_novtramites AS c ON c.as_afiliado_id = b.id
                LEFT JOIN as_tramites AS d ON d.id = c.as_tramite_id
                LEFT JOIN as_tipnovedades AS e ON d.id = c.as_tipnovedade_id
                WHERE c.id IS NOT NULL AND d.estado = 'Radicado' AND b.as_regimene_id = 2" );

        array_push($this->respuesta,[
            'tipo' => 'info',
            'titulo' => 'Afiliados con novedad validada',
            'afiliados' => $validados]);
    }
}