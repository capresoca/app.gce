<?php
/**
 * Created by PhpStorm.
 * User: Wilfredo
 * Date: 12/02/2020
 * Time: 3:17 PM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadoresV2;


use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorV3;
use App\Models\Aseguramiento\Procesos\AsR5;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class R5 extends ProcesadorV3
{
    public function __construct(Request $request)
    {
        parent::__construct( $request );
        $this->campos = "serial,
                        epssol,
                        tipoIdentificacion,
                        identificacion,
                        epsact,
                        consInterno,
                        tipoIdentificacion1,
                        identificacion1,
                        resultado,
                        causal,
                        fechaAprobacion";
    }

    public function procesar()
    {
        $r5temp = $this->cargarATablaTemporal('as_r5s');

        $respuesta = $this->cargarATabla('as_r5s');

        $this->createIndiceIdentificacion($r5temp);

        $this->validarAfiliadoExiste($r5temp);

        $this->afiliadoFallecido($r5temp);

        if(count($this->respuesta)){
            DB::statement("DROP TABLE {$r5temp}");
            AsR5::where('as_svid_id', $respuesta->id)->delete();
            $respuesta->delete();
            return;
        }

        $this->notificarActivados($r5temp);
        $this->notificarPospuestos($r5temp);

        $this->activarAfiliados($r5temp);
        $this->posponerActivacion($r5temp);

        DB::statement( "DROP TABLE IF EXISTS {$r5temp}" );


    }
    private function notificarActivados($r5temp)
    {
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''))");

        $activados = DB::select("select c.tipo_identificacion,c.identificacion,c.nombre_completo from
             (
                SELECT MAX(a.id) AS id, a.tipo_tramite, a.estado FROM
                 as_tramites AS a
                 LEFT JOIN as_traslatramites AS b ON b.as_tramite_id=a.id
                 LEFT JOIN as_afiliados AS c ON c.id = b.as_afiliado_id
                 LEFT JOIN {$r5temp} AS d ON d.tipoIdentificacion=c.tipo_identificacion AND d.identificacion=c.identificacion
                 WHERE a.tipo_tramite = 'Traslado Contributivo' AND d.epssol IS NOT NULL AND d.resultado = 1
                 GROUP BY b.as_afiliado_id
             )AS a 
             LEFT JOIN as_tramites AS g ON g.id = a.id
             LEFT JOIN as_traslatramites AS b ON b.as_tramite_id=g.id
             LEFT JOIN as_tramites_s1_val AS e ON e.as_tramite_id = a.id
             LEFT JOIN as_afiliados AS c ON c.id = b.as_afiliado_id
             LEFT JOIN  (
                                SELECT max(a.id) AS id, a.as_afiliado_id FROM as_formtrasmovs AS a
                                WHERE a.tipo = 'Traslado'
                                GROUP BY a.id 
                            ) AS k ON k.as_afiliado_id = c.id
             LEFT JOIN as_formtrasmovs AS f ON f.id = k.id
             LEFT JOIN {$r5temp} AS d ON d.tipoIdentificacion=c.tipo_identificacion AND d.identificacion=c.identificacion
             WHERE d.resultado='1' AND f.tipo = 'Traslado' AND a.tipo_tramite = 'Traslado Contributivo' AND g.estado != 'Aprobado' AND c.estado NOT IN (3,8)\");");

        array_push($this->respuesta,[
            'tipo' => 'info',
            'titulo' => 'Afiliados que se activaron',
            'afiliados' => $activados
        ]);

    }
    private function notificarPospuestos($r5temp)
    {
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''))");
        $pospuestos = DB::select("select c.tipo_identificacion, c.identificacion,c.nombre_completo from
              (
                 SELECT MAX(a.id) AS id, a.tipo_tramite, a.estado FROM
                  as_tramites AS a
                  LEFT JOIN as_traslatramites AS b ON b.as_tramite_id=a.id
                  LEFT JOIN as_afiliados AS c ON c.id = b.as_afiliado_id
                  LEFT JOIN {$r5temp} AS d ON d.tipoIdentificacion=c.tipo_identificacion AND d.identificacion=c.identificacion
                  WHERE a.tipo_tramite = 'Traslado Subsidiado' AND d.epssol IS NOT NULL AND d.resultado = 0
                  GROUP BY b.as_afiliado_id
              )AS a 
              LEFT JOIN as_tramites AS g ON g.id = a.id
              LEFT JOIN as_traslatramites AS b ON b.as_tramite_id=g.id
              LEFT JOIN as_tramites_s1_val AS e ON e.as_tramite_id = a.id
              LEFT JOIN as_afiliados AS c ON c.id = b.as_afiliado_id
              LEFT JOIN     (
                                SELECT max(a.id) AS id, a.as_afiliado_id FROM as_formtrasmovs AS a
                                WHERE a.tipo = 'Traslado'
                                GROUP BY a.id 
                            ) AS k ON k.as_afiliado_id = c.id
              LEFT JOIN as_formtrasmovs AS f ON f.id = k.id
              LEFT JOIN {$r5temp} AS d ON d.tipoIdentificacion=c.tipo_identificacion AND d.identificacion=c.identificacion
              WHERE a.tipo_tramite = 'Traslado Subsidiado' AND f.id IS NOT NULL AND d.resultado=0 AND c.estado NOT IN (3,8) AND f.tipo = 'Traslado' AND d.causal= '7' group by c.id");

        array_push($this->respuesta,[
            'tipo' => 'info',
            'titulo' => 'Afiliados que se activaran en una fecha posterior',
            'afiliados' => $pospuestos
        ]);

    }

    private function activarAfiliados($r5temp)
    {
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''))");
        DB::statement("
                            update
                             (
                                SELECT MAX(a.id) AS id, a.tipo_tramite, a.estado FROM
                                 as_tramites AS a
                                 LEFT JOIN as_traslatramites AS b ON b.as_tramite_id=a.id
                                 LEFT JOIN as_afiliados AS c ON c.id = b.as_afiliado_id
                                 LEFT JOIN {$r5temp} AS d ON d.tipoIdentificacion=c.tipo_identificacion AND d.identificacion=c.identificacion
                                 WHERE a.tipo_tramite = 'Traslado Contributivo' AND d.epssol IS NOT NULL AND d.resultado = 1
                                 GROUP BY b.as_afiliado_id
                             )AS a 
                             LEFT JOIN as_tramites AS g ON g.id = a.id
                             LEFT JOIN as_traslatramites AS b ON b.as_tramite_id=g.id
                             LEFT JOIN as_tramites_s1_val AS e ON e.as_tramite_id = a.id
                             LEFT JOIN as_afiliados AS c ON c.id = b.as_afiliado_id
                             LEFT JOIN  (
                                                SELECT max(a.id) AS id, a.as_afiliado_id FROM as_formtrasmovs AS a
                                                WHERE a.tipo = 'Traslado'
                                                GROUP BY a.id 
                                            ) AS k ON k.as_afiliado_id = c.id
                             LEFT JOIN as_formtrasmovs AS f ON f.id = k.id
                             LEFT JOIN {$r5temp} AS d ON d.tipoIdentificacion=c.tipo_identificacion AND d.identificacion=c.identificacion
                             SET c.estado = 3, g.estado='Aprobado',  f.fecha_traslado = if(concat(SUBSTRING(d.fechaAprobacion,7,4),'-',SUBSTRING(d.fechaAprobacion,4,2),'-',SUBSTRING(d.fechaAprobacion,1,2))='1900-01-01',e.fecha_afiliacion,(concat(SUBSTRING(d.fechaAprobacion,7,4),'-',SUBSTRING(d.fechaAprobacion,4,2),'-',SUBSTRING(d.fechaAprobacion,1,2)))) 
                             WHERE d.resultado='1' AND f.tipo = 'Traslado' AND a.tipo_tramite = 'Traslado Subsidiado' AND g.estado != 'Aprobado' AND c.estado NOT IN (3,8)  ");
    }

    private function posponerActivacion($r5temp)
    {
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''))");
        DB::statement("UPDATE
              (
                 SELECT MAX(a.id) AS id, a.tipo_tramite, a.estado FROM
                  as_tramites AS a
                  LEFT JOIN as_traslatramites AS b ON b.as_tramite_id=a.id
                  LEFT JOIN as_afiliados AS c ON c.id = b.as_afiliado_id
                  LEFT JOIN {$r5temp} AS d ON d.tipoIdentificacion=c.tipo_identificacion AND d.identificacion=c.identificacion
                  WHERE a.tipo_tramite = 'Traslado Contributivo' AND d.epssol IS NOT NULL AND d.resultado = 0
                  GROUP BY b.as_afiliado_id
              )AS a 
              LEFT JOIN as_tramites AS g ON g.id = a.id
              LEFT JOIN as_traslatramites AS b ON b.as_tramite_id=g.id
              LEFT JOIN as_tramites_s1_val AS e ON e.as_tramite_id = a.id
              LEFT JOIN as_afiliados AS c ON c.id = b.as_afiliado_id
              LEFT JOIN     (
                                SELECT max(a.id) AS id, a.as_afiliado_id FROM as_formtrasmovs AS a
                                WHERE a.tipo = 'Traslado'
                                GROUP BY a.id 
                            ) AS k ON k.as_afiliado_id = c.id
              LEFT JOIN as_formtrasmovs AS f ON f.id = k.id
              LEFT JOIN {$r5temp} AS d ON d.tipoIdentificacion=c.tipo_identificacion AND d.identificacion=c.identificacion
              SET c.estado = 3, g.estado='Aprobado', f.fecha_traslado = concat(SUBSTRING(d.fechaAprobacion,7,4),'-',SUBSTRING(d.fechaAprobacion,4,2),'-',SUBSTRING(d.fechaAprobacion,1,2))
              WHERE a.tipo_tramite = 'Traslado Contributivo' AND f.id IS NOT NULL AND d.resultado=0 AND c.estado NOT IN (3,8) AND f.tipo = 'Traslado' AND d.causal= '7' ");
    }

}