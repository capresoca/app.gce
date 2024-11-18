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
use App\Models\Enums\ETipoBdua;
use Illuminate\Support\Facades\Log;

class R5 extends ProcesadorV3
{
    
    protected $vigencia;
    
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
        //$r5temp = $this->cargarATablaTemporal('as_r5s');
        $r5temp = $this->cargarATablaTemporalActivacion('as_s5s');
        
        $registros      = DB::select("select d.informacion,d.tipo_identificacion_afiliado,d.numero_identificacion_afiliado as identificacion,
                                            a.primer_apellido,a.segundo_apellido,a.primer_nombre,a.segundo_nombre,
                                            a.fecha_nacimiento,if(a.genero=1,'F','M') as genero, a.consecutivo_afiliado, f.tipo_traslado, f.id
                                                 from log_ms_encabezado e
                                                    inner join log_ms_detalle d on
                                                    e.consecutivo_log_ms = d.consecutivo_log_ms
                                                    inner join log_ms_encabezado enc on
                                                    enc.consecutivo_log_ms = d.consecutivo_log_ms AND enc.tipo = ".ETipoBdua::getIndice(ETipoBdua::R5)->getId()."
                                                    inner join af_afiliado_ms a on
                                                    a.consecutivo_ns = d.consecutivo_ms
                                                    inner join as_formtrasmovs f on
                                                    f.as_afiliado_id = a.consecutivo_afiliado and a.tipo_traslado = 'R'
                                                    where a.consecutivo_vigencia = {$this->vigencia}  and a.tipo_traslado = 'R'
                                                    AND f.fecha_efectiva_traslado IS NULL ");
        /* Log::info('Sql Vigencia: ',array("select d.informacion,d.tipo_identificacion_afiliado,d.numero_identificacion_afiliado as identificacion,
         a.primer_apellido,a.segundo_apellido,a.primer_nombre,a.segundo_nombre,
         a.fecha_nacimiento,if(a.genero=1,'F','M') as genero, a.consecutivo_afiliado, f.tipo_traslado, f.id
         from log_ms_encabezado e
         inner join log_ms_detalle d on
         e.consecutivo_log_ms = d.consecutivo_log_ms
         inner join af_afiliado_ms a on
         a.consecutivo_ns = d.consecutivo_ms
         inner join as_formtrasmovs f on
         f.as_afiliado_id = a.consecutivo_afiliado and a.tipo_traslado = 'S'
         where a.consecutivo_vigencia = {$this->vigencia}  and a.tipo_traslado = 'S'
         AND f.fecha_efectiva_traslado IS NULL "));*/
        
        /*$CODIGO_DEPARTAMENTO        = 17;
         $CODIGO_MUNICIPIO           = 18;
         $CODIGO_ZONA                = 19;
         $FECHA_AFILIACION           = 20;
         $TIPO_POBLACION             = 21;
         $SISBEN                     = 22;
         $TIPO_SUB                   = 23;*/
        $existen                    = array();
        foreach ($registros as $r) {
            
            $temporal       = array();
            
            $partes         = explode(',', $r->informacion);
            Log::info('Partes informacion: ',array($partes));
            if($partes[7]==1) {
                if(empty($partes[9])) {
                    $fechaEfectiva      = NULL;
                    switch ($r->tipo_traslado) {
                        case 0:
                        case 3:
                        case 5:{
                            $fechaEfectiva  = date('Y-m-d');
                        }break;
                        case 1:
                        case 4:{
                            //date("d-m-Y",strtotime($fecha_actual."+ 1 month"));
                            $fechaEfectiva  = date("Y-m-01",strtotime(date('Y-m-d')."+ 1 month"));
                        }break;
                        case 2:{
                            //date("d-m-Y",strtotime($fecha_actual."+ 1 month"));
                            $fechaEfectiva  = date("Y-m-01",strtotime(date('Y-m-d')."+ 2 month"));
                        }break;
                    }
                } else {
                    $fechaEfectiva     = $partes[9];
                }
                DB::update("update as_formtrasmovs set fecha_efectiva_traslado = '{$fechaEfectiva}' WHERE id = ".$r->id);
            }
        }
        
        $registros      = DB::select("SELECT l.informacion,l.tipo_identificacion_afiliado,l.numero_identificacion_afiliado as identificacion,
                                            a.primer_apellido,a.segundo_apellido,a.primer_nombre,a.segundo_nombre,
                                            a.fecha_nacimiento,if(a.genero=1,'F','M') as genero, a.consecutivo_afiliado, f.tipo_traslado,fecha_efectiva_traslado
                                        FROM as_formtrasmovs f
                                        inner join af_afiliado_ms a on
                                        f.as_afiliado_id = a.consecutivo_afiliado and a.tipo_traslado = 'R'
                                        inner join log_ms_detalle l on
                                        l.consecutivo_ms = a.consecutivo_ns
                                        inner join log_ms_encabezado enc on
                                        enc.consecutivo_log_ms = l.consecutivo_log_ms AND enc.tipo = ".ETipoBdua::getIndice(ETipoBdua::R5)->getId()."
                                        inner join as_afiliados af on
                                        af.id = a.consecutivo_afiliado
                                        where a.consecutivo_log_ms IS NOT NULL AND f.estado = 'Radicado' AND a.tipo_traslado = 'R' and af.estado not in(3) and fecha_efectiva_traslado <= '".date('Y-m-d')."' ");
        
        foreach ($registros as $r) {
            
            if(!empty($existen)) {
                continue;
            }
            
            $temporal       = array();
            
            $partes         = explode(',', $r->informacion);
            
            $temporal[]     = "'".$partes[0]."'";
            $temporal[]     = "'".$partes[1]."'";
            $temporal[]     = "'".$partes[2]."'";
            $temporal[]     = "'".$partes[3]."'";
            $temporal[]     = "'".$partes[4]."'";
            $temporal[]     = "'".$partes[5]."'";
            $temporal[]     = "'".$partes[6]."'";
            $temporal[]     = "'".$partes[7]."'";
            $temporal[]     = "'".$partes[8]."'";
            $temporal[]     = "'".$r->fecha_efectiva_traslado."'";
            
            DB::insert("INSERT INTO {$r5temp} (".$this->campos.") VALUES (".implode(',', $temporal).") ");
            
            $existen['A'.$r->tipo_identificacion_afiliado.$r->identificacion]       = 'OK';
        }

        //$respuesta = $this->cargarATabla('as_r5s');

        $this->createIndiceIdentificacion($r5temp);

        $this->validarAfiliadoExiste($r5temp);

        $this->afiliadoFallecido($r5temp);

        /*if(count($this->respuesta)){
            DB::statement("DROP TABLE {$r5temp}");
            AsR5::where('as_svid_id', $respuesta->id)->delete();
            $respuesta->delete();
            return;
        }*/

        $this->notificarActivados($r5temp);
        $this->notificarPospuestos($r5temp);

        $this->activarAfiliados($r5temp);
        //$this->posponerActivacion($r5temp);

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

    /**
     * @return mixed
     */
    public function getVigencia()
    {
        return $this->vigencia;
    }
    
    /**
     * @param mixed $vigencia
     */
    public function setVigencia($vigencia)
    {
        $this->vigencia = $vigencia;
    }
}