<?php
/**
 * Created by PhpStorm.
 * User: Wilfredo
 * Date: 12/02/2020
 * Time: 2:50 PM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadoresV2;


use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorV3;
use App\Models\Aseguramiento\Procesos\AsR1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class R1VAL extends ProcesadorV3
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
        $r1ValTemp = $this->cargarATablaTemporal('r1');

        $respuesta = $this->cargarATabla('r1');

        $this->createIndiceIdentificacion($r1ValTemp);
        $this->validarAfiliadoExiste($r1ValTemp);

        $this->afiliadoFallecido($r1ValTemp);

        if(count($this->respuesta)){
            DB::statement("DROP TABLE {$r1ValTemp}");
            AsR1::where('as_svid_id', $respuesta->id)->delete();
            $respuesta->delete();
            return;
        };

        $this->updateTramite($r1ValTemp);

        $this->insertTramiteR1Val($r1ValTemp);

    }

    private function updateTramite(string $r1temp): void
    {
        DB::statement("
             UPDATE
             (
                SELECT MAX(z.id) AS id, z.tipo_tramite FROM
                 as_tramites AS z
                 LEFT JOIN as_traslatramites AS b ON b.as_tramite_id=z.id
                 LEFT JOIN as_afiliados AS c ON c.id = b.as_afiliado_id
                 LEFT JOIN {$r1temp} AS d ON d.tipoIdentificacion=c.tipo_identificacion AND d.identificacion=c.identificacion
                 WHERE z.tipo_tramite = 'Traslado Contributivo' AND d.eapbC IS NOT NULL
                 GROUP BY b.as_afiliado_id
             )AS a 
             LEFT JOIN as_tramites as tr on tr.id = a.id
             LEFT JOIN as_traslatramites AS b ON b.as_tramite_id=a.id
             LEFT JOIN as_afiliados AS c ON c.id = b.as_afiliado_id
             LEFT JOIN {$r1temp} AS d ON d.tipoIdentificacion=c.tipo_identificacion AND d.identificacion=c.identificacion
             SET tr.estado = 'Enviado'
             WHERE a.tipo_tramite = 'Traslado Contributivo' AND d.eapbC IS NOT NULL AND c.estado!='3' and c.estado != 8
        ");
    }

    private function insertTramiteR1Val(string $r1temp)
    {
        DB::statement("
            INSERT INTO as_tramites_r1_val (as_tramite_id,fecha_afiliacion)
            SELECT a.id,
            concat(SUBSTRING(d.fecAfiliacion,7,4),'-',SUBSTRING(d.fecAfiliacion,4,2),'-',SUBSTRING(d.fecAfiliacion,1,2)) AS fecha_afiliacion
            from
             (
                SELECT MAX(a.id) AS id, a.tipo_tramite FROM
                 as_tramites AS a
                 LEFT JOIN as_traslatramites AS b ON b.as_tramite_id=a.id
                 LEFT JOIN as_afiliados AS c ON c.id = b.as_afiliado_id
                 LEFT JOIN {$r1temp} AS d ON d.tipoIdentificacion=c.tipo_identificacion AND d.identificacion=c.identificacion
                 WHERE a.tipo_tramite = 'Traslado Contributivo' AND d.eapbC IS NOT NULL
                 GROUP BY b.as_afiliado_id
             )AS a 
             LEFT JOIN as_traslatramites AS b ON b.as_tramite_id=a.id
             LEFT JOIN as_afiliados AS c ON c.id = b.as_afiliado_id
             LEFT JOIN {$r1temp} AS d ON d.tipoIdentificacion=c.tipo_identificacion AND d.identificacion=c.identificacion
             WHERE a.tipo_tramite = 'Traslado Contributivo' AND d.eapbC IS NOT NULL
             ORDER BY c.identificacion 
        ");
    }

}