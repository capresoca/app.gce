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
use App\Models\Aseguramiento\AsFormtrasmov;
use App\Models\Enums\ETipoBdua;
use App\Models\Aseguramiento\BDUA\AfAfiliadoMs;

class S1Automatico1 extends ProcesadorV3
{
    //Request $request
    public function __construct()
    {
        //$request
        parent::__construct();
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

    public function procesaActivacion($tipo=NULL) {
        $tabla_s1Automatico_temporal = $this->cargarATablaTemporalActivacion('as_s1autvals');
        
        $registros      = DB::select("SELECT l.informacion,l.tipo_identificacion_afiliado,l.numero_identificacion_afiliado as identificacion,
                                            a.primer_apellido,a.segundo_apellido,a.primer_nombre,a.segundo_nombre,
                                            a.fecha_nacimiento,if(a.genero=1,'F','M') as genero, a.consecutivo_afiliado,a.consecutivo_ns
                                        FROM as_formtrasmovs f 
                                        inner join af_afiliado_ms a on 
                                        f.as_afiliado_id = a.consecutivo_afiliado and a.tipo_traslado = 'S'
                                        inner join log_ms_detalle l on 
                                        l.consecutivo_ms = a.consecutivo_ns
                                        inner join log_ms_encabezado enc on 
                                        enc.consecutivo_log_ms = l.consecutivo_log_ms AND enc.tipo = ".ETipoBdua::getIndice(ETipoBdua::S1_AUTOMATICO)->getId()." 
                                        inner join as_afiliados af on 
                                        af.id = a.consecutivo_afiliado
                                        where a.consecutivo_log_ms IS NOT NULL AND f.estado = 'Radicado' AND a.tipo_traslado = 'S' and af.estado not in(3) and fecha_efectiva_traslado <= '".date('Y-m-d')."'");
        
        //$guardar        = array();
        $CODIGO_DEPARTAMENTO        = 17;
        $CODIGO_MUNICIPIO           = 18;
        $CODIGO_ZONA                = 19;
        $FECHA_AFILIACION           = 20;
        $TIPO_POBLACION             = 21;
        $SISBEN                     = 22;
        $TIPO_SUB                   = 23;
        $existen                    = array();
        //Log::info('Registros: ', array($registros));
        foreach ($registros as $r) {
            //Log::info('Pasa Registro: '.$r->informacion);
            
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
            
            $afAfiliadoMs               = AfAfiliadoMs::find($r->consecutivo_ns);
            $afAfiliadoMs->estado_procesado        = 1;
            $afAfiliadoMs->save();
        }
        
        $this->createIndiceIdentificacion($tabla_s1Automatico_temporal);
        
        $this->validarAfiliadoExiste($tabla_s1Automatico_temporal);
        
        $this->afiliadoFallecido($tabla_s1Automatico_temporal);
        
        $this->notificarActivos($tabla_s1Automatico_temporal);
        
        $this->notificarActivar($tabla_s1Automatico_temporal);
        
        $this->updateAfiliadoYTramite($tabla_s1Automatico_temporal);
        
        DB::statement(/** @lang text */ "DROP TABLE IF EXISTS {$tabla_s1Automatico_temporal}");
        //$respuesta = $this->cargarATabla('as_s1autvals');
    }
    
    public function procesar()
    {

        $tabla_s1Automatico_temporal = $this->cargarATablaTemporal('as_s1autvals');

        $respuesta = $this->cargarATabla('as_s1autvals');

        $this->createIndiceIdentificacion($tabla_s1Automatico_temporal);

        $this->validarAfiliadoExiste($tabla_s1Automatico_temporal);

        $this->afiliadoFallecido($tabla_s1Automatico_temporal);

        if(count($this->respuesta)){
            DB::statement(/** @lang text */ "DROP TABLE {$tabla_s1Automatico_temporal}");
            DB::table('as_s1autvals')->where('as_svid_id','=',$respuesta->id)->delete();
            $respuesta->delete();
            return;
        }

        $this->notificarActivos($tabla_s1Automatico_temporal);

        $this->notificarActivar($tabla_s1Automatico_temporal);

        $this->updateAfiliadoYTramite($tabla_s1Automatico_temporal);

        DB::statement(/** @lang text */ "DROP TABLE IF EXISTS {$tabla_s1Automatico_temporal}");

    }

    /**
     * @param string $tabla_s1Automatico_temporal
     */
    private function notificarActivos(string $tabla_s1Automatico_temporal): void
    {
        $afiliadosActivados = DB::select(/** @lang text */ "
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
        $afiliadosActivar = DB::select(/** @lang text */ "
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
        DB::statement(/** @lang text */ "
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