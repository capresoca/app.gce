<?php
/**
 * Created by PhpStorm.
 * User: Wilfredo
 * Date: 10/02/2020
 * Time: 10:20 AM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadoresV2;


use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorV3;
use App\Models\Aseguramiento\Procesos\AsR1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Enums\ETipoBdua;
use Illuminate\Support\Facades\Log;

class S1VAL extends ProcesadorV3
{
    protected $vigencia;
    
    public function __construct(Request $request = NULL)
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
        $tabla_s1Automatico_temporal = $this->cargarATablaTemporalActivacion('as_s1autvals');
        //$respuesta = $this->cargarATabla('as_s1autvals');
        $registros      = DB::select("select d.informacion,d.tipo_identificacion_afiliado,d.numero_identificacion_afiliado as identificacion,
                                            a.primer_apellido,a.segundo_apellido,a.primer_nombre,a.segundo_nombre,
                                            a.fecha_nacimiento,if(a.genero=1,'F','M') as genero, a.consecutivo_afiliado, f.tipo_traslado, f.id
                                                 from log_ms_encabezado e
                                                    inner join log_ms_detalle d on
                                                    e.consecutivo_log_ms = d.consecutivo_log_ms
                                                    inner join log_ms_encabezado enc on
                                                    enc.consecutivo_log_ms = d.consecutivo_log_ms AND enc.tipo = ".ETipoBdua::getIndice(ETipoBdua::S1_VAL)->getId()."
                                                    inner join af_afiliado_ms a on
                                                    a.consecutivo_ns = d.consecutivo_ms
                                                    inner join as_formtrasmovs f on
                                                    f.as_afiliado_id = a.consecutivo_afiliado and a.tipo_traslado = 'S'
                                                    where a.consecutivo_vigencia = {$this->vigencia}  and a.tipo_traslado = 'S'
                                                    AND f.fecha_efectiva_traslado IS NULL ");
        $existen                    = array();
        
        foreach ($registros as $r) {
            //$temporal       = array();
            $partes         = explode(',', $r->informacion);
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
            DB::update("update as_formtrasmovs set fecha_efectiva_traslado = '{$fechaEfectiva}' WHERE id = ".$r->id);
        }

        Log::info('SQL S1VAL: '."SELECT l.informacion,l.tipo_identificacion_afiliado,l.numero_identificacion_afiliado as identificacion,
                                            a.primer_apellido,a.segundo_apellido,a.primer_nombre,a.segundo_nombre,
                                            a.fecha_nacimiento,if(a.genero=1,'F','M') as genero, a.consecutivo_afiliado, f.tipo_traslado,fecha_efectiva_traslado
                                        FROM as_formtrasmovs f
                                        inner join af_afiliado_ms a on
                                        f.as_afiliado_id = a.consecutivo_afiliado and a.tipo_traslado = 'S'
                                        inner join log_ms_detalle l on
                                        l.consecutivo_ms = a.consecutivo_ns
                                        inner join log_ms_encabezado enc on
                                        enc.consecutivo_log_ms = l.consecutivo_log_ms AND enc.tipo = ".ETipoBdua::getIndice(ETipoBdua::S1_VAL)->getId()."
                                        inner join as_afiliados af on
                                        af.id = a.consecutivo_afiliado
                                        where a.consecutivo_log_ms IS NOT NULL AND f.estado = 'Radicado' AND a.tipo_traslado = 'S' and af.estado not in(3) and fecha_efectiva_traslado <= '".date('Y-m-d')."' ");
        
        
        $registros      = DB::select("SELECT l.informacion,l.tipo_identificacion_afiliado,l.numero_identificacion_afiliado as identificacion,
                                            a.primer_apellido,a.segundo_apellido,a.primer_nombre,a.segundo_nombre,
                                            a.fecha_nacimiento,if(a.genero=1,'F','M') as genero, a.consecutivo_afiliado, f.tipo_traslado,fecha_efectiva_traslado
                                        FROM as_formtrasmovs f
                                        inner join af_afiliado_ms a on
                                        f.as_afiliado_id = a.consecutivo_afiliado and a.tipo_traslado = 'S'
                                        inner join log_ms_detalle l on
                                        l.consecutivo_ms = a.consecutivo_ns
                                        inner join log_ms_encabezado enc on
                                        enc.consecutivo_log_ms = l.consecutivo_log_ms AND enc.tipo = ".ETipoBdua::getIndice(ETipoBdua::S1_VAL)->getId()."
                                        inner join as_afiliados af on
                                        af.id = a.consecutivo_afiliado
                                        where a.consecutivo_log_ms IS NOT NULL AND f.estado = 'Radicado' AND a.tipo_traslado = 'S' and af.estado not in(3) and fecha_efectiva_traslado <= '".date('Y-m-d')."' ");
        
        $CODIGO_DEPARTAMENTO        = 17;
        $CODIGO_MUNICIPIO           = 18;
        $CODIGO_ZONA                = 19;
        $FECHA_AFILIACION           = 20;
        $TIPO_POBLACION             = 21;
        $SISBEN                     = 22;
        $TIPO_SUB                   = 23;
        Log::info('cantidad: '.count($registros));
        foreach ($registros as $r) {
            
            if(!empty($existen['A'.$r->tipo_identificacion_afiliado.$r->identificacion])) {
                continue;
            }
            
            $temporal       = array();
            
            $partes         = explode(',', $r->informacion);
            
            $temporal[]     = "'".$partes[0]."'";
            $temporal[]     = "'".$r->tipo_identificacion_afiliado."'";
            $temporal[]     = "'".$r->identificacion."'";
            $temporal[]     = "'".$r->primer_apellido."'";
            $temporal[]     = "'".$r->segundo_apellido."'";
            $temporal[]     = "'".$r->primer_nombre."'";
            $temporal[]     = "'".$r->segundo_nombre."'";
            $temporal[]     = "'".$r->fecha_nacimiento."'";
            $temporal[]     = "'".$r->genero."'";
            
            $temporal[]     = "'".$r->tipo_identificacion_afiliado."'";
            $temporal[]     = "'".$r->identificacion."'";
            $temporal[]     = "'".$r->primer_apellido."'";
            $temporal[]     = "'".$r->segundo_apellido."'";
            $temporal[]     = "'".$r->primer_nombre."'";
            $temporal[]     = "'".$r->segundo_nombre."'";
            $temporal[]     = "'".$r->fecha_nacimiento."'";
            $temporal[]     = "'".$r->genero."'";
            
            $temporal[]     = "'".$partes[$CODIGO_DEPARTAMENTO]."'";
            $temporal[]     = "'".$partes[$CODIGO_MUNICIPIO]."'";
            $temporal[]     = "'".$partes[$CODIGO_ZONA]."'";
            $temporal[]     = "'".$partes[$FECHA_AFILIACION]."'";
            $temporal[]     = "'".$partes[$TIPO_POBLACION]."'";
            $temporal[]     = "'".$partes[$SISBEN]."'";
            $temporal[]     = "'".$partes[$TIPO_SUB]."'";
            
            DB::insert("INSERT INTO {$tabla_s1Automatico_temporal} (".$this->campos.") VALUES (".implode(',', $temporal).") ");
            
            $existen['A'.$r->tipo_identificacion_afiliado.$r->identificacion]       = 'OK';
        }
        
        $this->createIndiceIdentificacion($tabla_s1Automatico_temporal);

        $this->validarDuplicados($tabla_s1Automatico_temporal);

        $this->validarAfiliadoExiste($tabla_s1Automatico_temporal);

        $this->afiliadoFallecido($tabla_s1Automatico_temporal);

        /*if(count($this->respuesta)){
            DB::statement("DROP TABLE {$tabla_s1Automatico_temporal}");
            AsR1::where('as_svid_id', $respuesta->id)->delete();
            $respuesta->delete();
            return;
        };*/
        Log::info('tabla: '.$tabla_s1Automatico_temporal);
        $this->updateTramite($tabla_s1Automatico_temporal);

        $this->insertTramiteS1Val($tabla_s1Automatico_temporal);
        DB::statement("DROP TABLE IF EXISTS {$tabla_s1Automatico_temporal}");
        Log::info('Logra Terminar S1');
    }

    private function updateTramite(string $tabla_s1Automatico_temporal): void
    {
        DB::statement("
             UPDATE
             (
                SELECT MAX(z.id) AS id, z.tipo_tramite FROM
                 as_tramites AS z
                 LEFT JOIN as_traslatramites AS b ON b.as_tramite_id=z.id
                 LEFT JOIN as_afiliados AS c ON c.id = b.as_afiliado_id
                 LEFT JOIN {$tabla_s1Automatico_temporal} AS d ON d.tipoIdentificacion=c.tipo_identificacion AND d.identificacion=c.identificacion
                 WHERE z.tipo_tramite = 'Traslado Subsidiado' AND d.codeps IS NOT NULL
                 GROUP BY b.as_afiliado_id
             )AS a 
             LEFT JOIN as_tramites as tr on tr.id = a.id
             LEFT JOIN as_traslatramites AS b ON b.as_tramite_id=a.id
             LEFT JOIN as_afiliados AS c ON c.id = b.as_afiliado_id
             LEFT JOIN {$tabla_s1Automatico_temporal} AS d ON d.tipoIdentificacion=c.tipo_identificacion AND d.identificacion=c.identificacion
             SET tr.estado = 'Enviado'
             WHERE a.tipo_tramite = 'Traslado Subsidiado' AND d.codeps IS NOT NULL AND c.estado!='3' and c.estado != 8
        ");
    }

    private function insertTramiteS1Val(string $tabla_s1Automatico_temporal)
    {
        DB::statement("
            INSERT INTO as_tramites_s1_val (as_tramite_id,fecha_afiliacion)
            SELECT a.id,
            concat(SUBSTRING(d.fechaAfiliacionEps,7,4),'-',SUBSTRING(d.fechaAfiliacionEps,4,2),'-',SUBSTRING(d.fechaAfiliacionEps,1,2)) AS fecha_afiliacion
            from
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
             LEFT JOIN {$tabla_s1Automatico_temporal} AS d ON d.tipoIdentificacion=c.tipo_identificacion AND d.identificacion=c.identificacion
             WHERE a.tipo_tramite = 'Traslado Subsidiado' AND d.codeps IS NOT NULL
             ORDER BY c.identificacion 
        ");
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