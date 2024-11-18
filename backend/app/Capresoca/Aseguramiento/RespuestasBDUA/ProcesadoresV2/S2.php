<?php
/**
 * Created by PhpStorm.
 * User: Wilfredo
 * Date: 6/02/2020
 * Time: 4:51 PM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadoresV2;


use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorV3;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\Procesos\AsS2;
use App\Models\General\GnTipdocidentidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Enums\ETipoBdua;
use App\Models\Aseguramiento\RespuestaDBUA;
use Illuminate\Support\Facades\Auth;

class S2 extends ProcesadorV3
{
    
    protected $vigencia;
    protected $fileName;
    
    public function __construct(Request $request = NULL)
    {
        parent::__construct();
        $this->campos = "serial,
                        codeps,
                        tipoIdentificacion,
                        identificacion,
                        primerApellido,
                        segundoApellido,
                        primerNombre,
                        segundoNombre,
                        codigoDepartamento,
                        codigoMunicipio,
                        fechaAfiliacion,
                        fechaUpc";
    }

    public function procesar()
    {
        $s2temporal = $this->cargarATablaTemporalActivacion('as_s2s');
        
        $registros      = DB::select("select d.informacion
                                                 from log_ms_encabezado e
                                                    inner join log_ms_detalle d on
                                                    e.consecutivo_log_ms = d.consecutivo_log_ms
                                                    where e.consecutivo_vigencia = {$this->vigencia} AND e.tipo = ".ETipoBdua::getIndice(ETipoBdua::S2)->getId()." ");

        //$respuesta = $this->cargarATabla('as_s2s');
        $fechaProceso   = $this->getFechaProceso('S2ARCHIVOFINAL');
        
        $respuesta      = RespuestaDBUA::create([
            'nombreArchivo' => 'S2ARCHIVOFINAL',
            'fechaProceso'  => $fechaProceso,
            'gs_usuario_id' => Auth::user()->id,
            'fS' => today()->toDateString()
        ]);
        
        $existen                    = array();
        foreach ($registros as $r) {
            
            $partes         = explode(',', $r->informacion);
            
            if(!empty($existen['A'.$partes[2].$partes[3]])) {
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
            $temporal[]     = "'".$partes[9]."'";
            $temporal[]     = "'".$partes[10]."'";
            $temporal[]     = "'".$partes[11]."'";
            
            DB::insert("INSERT INTO {$s2temporal} (".$this->campos.") VALUES (".implode(',', $temporal).") ");
            
            $existen['A'.$partes[2].$partes[3]]       = 'OK';
        }

        $this->createIndiceIdentificacion($s2temporal);
        
        $this->validarAfiliadoExiste($s2temporal);

        if(count($registros)<=0){
            DB::statement("DROP TABLE {$s2temporal}");
            AsS2::where('as_svid_id', $respuesta->id)->delete();
            $respuesta->delete();
            return;
        }

        $this->solicitudesAcrear($s2temporal);

        $this->crearSolicitudes($s2temporal,$respuesta);
        DB::statement( "DROP TABLE IF EXISTS {$s2temporal}" );

    }

    private function solicitudesAcrear($s2temporal)
    {
        $afiliadosSolicitados = DB::select("SELECT 
        b.id,b.tipo_identificacion,b.identificacion,b.nombre_completo
        FROM {$s2temporal} AS a
        LEFT JOIN as_afiliados  AS b ON b.tipo_identificacion=a.tipoIdentificacion AND b.identificacion=a.identificacion
        left join as_epss as c on a.codeps = c.cod_habilitacion
        WHERE b.id IS not null");

        array_push($this->respuesta,[
            'tipo' => 'info',
            'titulo' => 'Afiliados que se estan solicitando en traslado',
            'afiliados' => $afiliadosSolicitados
        ]);

    }

    private function crearSolicitudes($s2temporal,$respuesta)
    {
        DB::statement("insert into as_soltraslados (
                id,
                bduaserial,
                as_afiliado_id,
                as_eps_id,
                fecha_solicita,
                fecha_apropiacion,
                respuesta,
                as_cautraslado_id,
                fecha_factible,
                nombre1S2,
                nombre2S2,
                apellido1S2,
                apellido2S2,
                cod_departamentoS2,
                cod_municipioS2,
                tip_docu_cabfamiliaS2,
                identificacion_cabfamiliaS2,
                estado,
                as_regimen_id,
                as_svid_id
                )
                SELECT 
                null,
                a.serial bduaserial, 
                b.id as_afiliado_id,
                c.id as_eps_id,
                a.fechaAfiliacion as fecha_solicita,
                a.fechaUpc as fecha_apropiacion,
                if(FORMAT(((UNIX_TIMESTAMP(a.fechaAfiliacion)-UNIX_TIMESTAMP(b.fecha_afiliacion))/(60*60*24)),0) < 365, 'Aprobado','Negado') as respuesta,
                if(FORMAT(((UNIX_TIMESTAMP(a.fechaAfiliacion)-UNIX_TIMESTAMP(b.fecha_afiliacion))/(60*60*24)),0) < 365, 1,14) as as_cautraslado_id,
                last_day(now()) fecha_factible,
                a.primerNombre nombre1S2,
                a.segundoNombre nombre2S2,
                a.primerApellido apellido1S2,
                a.segundoApellido apellido2S2,
                a.codigoDepartamento cod_departamentoS2,
                a.codigoMunicipio cod_municipioS2,
                a.tipoIdentificacion tip_docu_cabfamiliaS2,
                a.identificacion identificacion_cabfamiliaS2,
                'Validado' estado,
                b.as_regimene_id,
                {$respuesta->id}
                FROM {$s2temporal} AS a
                LEFT JOIN as_afiliados  AS b ON b.tipo_identificacion=a.tipoIdentificacion AND b.identificacion=a.identificacion
                left join as_epss as c on a.codeps = c.cod_habilitacion
                WHERE b.id IS not null");
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
    
    /**
     * @return mixed
     */
    public function getFileName()
    {
        return $this->fileName;
    }
    
    /**
     * @param mixed $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }
    
}
