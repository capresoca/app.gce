<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 26/09/2018
 * Time: 5:26 PM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores;

use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorV2;
use App\Models\Aseguramiento\AsBduaproceso;
use Illuminate\Support\Facades\DB;


class S5 extends ProcesadorV2
{

    public function __construct($csvPath, AsBduaproceso $proceso = null)
    {
        parent::__construct($csvPath, $proceso);
        $this->campos = "serial,
        epssol,
        tipoIdentificacion,
        identificacion,
        epsact,
        tipoIdentificacion1,
        identificacion1,
        resultado,
        causal,
        fechaAprobacion";

    }

    public function procesar()
    {
        $tabla_s5_temporal = $this->cargarATabla('s5');
        $this->createIndiceIdentificacion($tabla_s5_temporal);
        $this->validarDuplicados($tabla_s5_temporal);
        $this->validarAfiliadoExiste($tabla_s5_temporal);
        $this->afiliadoFallecido($tabla_s5_temporal);
        $this->notificarAfiliadosActivar($tabla_s5_temporal);
        $this->notificarActivos($tabla_s5_temporal);
        $this->notificarAfiliadosActivar($tabla_s5_temporal);
        $this->activarAfiliados($tabla_s5_temporal);

    }

    private function notificarAfiliadosActivar($tabla_s5_temporal)
    {

        $afiliados_activar = DB::select("
            SELECT concat(a.tipoIdentificacion,'-',a.identificacion,' - Error: Afiliado fallecido') as descri,
            if(e.fecha_afiliacion IS NULL,d.fechaAprobacion,e.fecha_afiliacion) AS fecha_afiliacion
            from
             (
                SELECT MAX(a.id) AS id, a.tipo_tramite FROM
                 as_tramites AS a
                 LEFT JOIN as_traslatramites AS b ON b.as_tramite_id=a.id
                 LEFT JOIN as_afiliados AS c ON c.id = b.as_afiliado_id
                 LEFT JOIN {$tabla_s5_temporal} AS d ON d.tipoIdentificacion=c.tipo_identificacion AND d.identificacion=c.identificacion
                 WHERE a.tipo_tramite = 'Traslado Subsidiado' AND d.epssol IS NOT NULL AND d.resultado = 1
                 GROUP BY b.as_afiliado_id
             )AS a 
             LEFT JOIN as_traslatramites AS b ON b.as_tramite_id=a.id
             LEFT JOIN as_tramites_s1_val AS e ON e.as_tramite_id = a.id
             LEFT JOIN as_afiliados AS c ON c.id = b.as_afiliado_id
             LEFT JOIN {$tabla_s5_temporal} AS d ON d.tipoIdentificacion=c.tipo_identificacion AND d.identificacion=c.identificacion
             WHERE a.tipo_tramite = 'Traslado Subsidiado' AND d.epssol IS NOT NULL AND d.resultado = 1
             ORDER BY c.identificacion 
        ");

        $this->pushMonitor("AFILIADOS QUE SE ACTIVARON EN EL REGIMEN SUBSIDIADO : ". count($afiliados_activar), 'white--text');

        foreach ($afiliados_activar as $afiliado) {
            $this->pushMonitor($afiliado->descri,'info--text');
        }


    }

    private function activarAfiliados($tabla_temporal)
    {
        DB::statement("
            UPDATE
             (
                SELECT MAX(a.id) AS id, a.tipo_tramite FROM
                 as_tramites AS a
                 LEFT JOIN as_traslatramites AS b ON b.as_tramite_id=a.id
                 LEFT JOIN as_afiliados AS c ON c.id = b.as_afiliado_id
                 LEFT JOIN {$tabla_temporal} AS d ON d.tipoIdentificacion=c.tipo_identificacion AND d.identificacion=c.identificacion
                 WHERE a.tipo_tramite = 'Traslado Subsidiado' AND d.epssol IS NOT NULL AND d.resultado = 1
                 GROUP BY b.as_afiliado_id
             )AS a 
             LEFT JOIN as_traslatramites AS b ON b.as_tramite_id=a.id
             LEFT JOIN as_tramites_s1_val AS e ON e.as_tramite_id = a.id
             LEFT JOIN as_afiliados AS c ON c.id = b.as_afiliado_id
             LEFT JOIN {$tabla_temporal} AS d ON d.tipoIdentificacion=c.tipo_identificacion AND d.identificacion=c.identificacion
             SET c.estado = 3,
             WHERE a.tipo_tramite = 'Traslado Subsidiado' AND d.epssol IS NOT NULL AND d.resultado = 1 AND c.estado != 3
        ");
    }

    protected function notificarActivos($tabla_temporal){
        $afiliados_activos = DB::select("
            SELECT concat(a.tipoIdentificacion,'-',a.identificacion) as descri,
            if(e.fecha_afiliacion IS NULL,d.fechaAprobacion,e.fecha_afiliacion) AS fecha_afiliacion
            from
             (
                SELECT MAX(a.id) AS id, a.tipo_tramite FROM
                 as_tramites AS a
                 LEFT JOIN as_traslatramites AS b ON b.as_tramite_id=a.id
                 LEFT JOIN as_afiliados AS c ON c.id = b.as_afiliado_id
                 LEFT JOIN {$tabla_temporal} AS d ON d.tipoIdentificacion=c.tipo_identificacion AND d.identificacion=c.identificacion
                 WHERE a.tipo_tramite = 'Traslado Subsidiado' AND d.epssol IS NOT NULL AND d.resultado = 1
                 GROUP BY b.as_afiliado_id
             )AS a 
             LEFT JOIN as_traslatramites AS b ON b.as_tramite_id=a.id
             LEFT JOIN as_tramites_s1_val AS e ON e.as_tramite_id = a.id
             LEFT JOIN as_afiliados AS c ON c.id = b.as_afiliado_id
             LEFT JOIN {$tabla_temporal} AS d ON d.tipoIdentificacion=c.tipo_identificacion AND d.identificacion=c.identificacion
             WHERE a.tipo_tramite = 'Traslado Subsidiado' AND d.epssol IS NOT NULL AND c.estado = 3
             ORDER BY c.identificacion");

        foreach ($afiliados_activos as $afiliados_activo){
            $this->pushMonitor($afiliados_activo->descri,'info--text');
        }

    }

}


