<?php
/**
 * Created by PhpStorm.
 * User: Wilfredo
 * Date: 4/02/2020
 * Time: 3:45 PM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadoresV2;


use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorV3;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MSVAL2 extends ProcesadorV3
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->campos = "
                codigoEntidad,
                tipoIden,
                identificacion,
                primerApellido,
                segundoApellido,
                primerNombre,
                segundoNombre,
                fechaNacimiento,
                genero,
                codigoDepartamento,
                codigoMunicipio,
                zona,
                fechaAfiliacionSistema,
                tipoPoblacion,
                nivelSisben,
                ipsPrimaria,
                condicionDiscapacidad,
                tipDocCabeza,
                identCabeza,
                parentezco,
                etnia";
    }

    public function procesar()
    {
        $this->cargarATabla('ms_val');
        $tablaTemporal = $this->cargarATablaTemporal('ms_val');

        $this->respuestaActivados( $tablaTemporal );
        $this->respuestaActivos($tablaTemporal);

        DB::statement("UPDATE
            {$tablaTemporal} AS a
            LEFT JOIN as_afiliados AS b ON b.tipo_identificacion = a.tipoIden AND b.identificacion = a.identificacion
            LEFT JOIN as_formafiliaciones AS c ON c.as_afiliado_id = b.id
            SET b.estado=3, c.estado='Procesado'
            WHERE c.id IS NOT NULL AND b.estado IN (1,2) AND c.estado = 'Radicado' AND c.as_regimene_id = 2");

        DB::statement( "DROP TABLE IF EXISTS {$tablaTemporal}" );

    }

    /**
     * @param $tablaTemporal
     */
    private function respuestaActivados($tablaTemporal): void
    {
        $activados = DB::select( "SELECT b.id, b.tipo_identificacion, b.identificacion,b.nombre_completo from {$tablaTemporal} AS a 
            LEFT JOIN as_afiliados AS b ON b.tipo_identificacion = a.tipoIden AND b.identificacion = a.identificacion
            LEFT JOIN as_formafiliaciones AS c ON c.as_afiliado_id = b.id
            WHERE c.id IS NOT NULL AND b.estado IN (1,2) AND c.estado = 'Radicado' AND c.as_regimene_id = 2" );

        if(!$activados){
            return;
        }

        array_push($this->respuesta,[
            'tipo' => 'info',
            'titulo' => 'Afiliados Activados en Regimen Subsidiado',
            'afiliados' => $activados]);
    }

    private function respuestaActivos($tablaTemporal): void
    {
        $activos = DB::select( "SELECT b.id, b.tipo_identificacion, b.identificacion,b.nombre_completo from {$tablaTemporal} AS a 
            LEFT JOIN as_afiliados AS b ON b.tipo_identificacion = a.tipoIden AND b.identificacion = a.identificacion
            LEFT JOIN as_formafiliaciones AS c ON c.as_afiliado_id = b.id
            WHERE b.estado = 3");

        if(!$activos){
            return;
        }

        array_push($this->respuesta,[
            'tipo' => 'info',
            'titulo' => 'Afiliados ya se encuentran activos en Regimen Subsidiado',
            'afiliados' => $activos]);
    }
}