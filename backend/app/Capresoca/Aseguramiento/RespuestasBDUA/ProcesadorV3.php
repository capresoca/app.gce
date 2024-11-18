<?php
/**
 * Created by PhpStorm.
 * User: Wilfredo
 * Date: 4/02/2020
 * Time: 4:14 PM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA;


use App\Models\Aseguramiento\RespuestaDBUA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProcesadorV3
{
    protected $campos;
    protected $respuesta = [];

    //Request $request = NULL
    public function __construct()
    {
        //$this->request = $request;
    }

    protected function cargarATablaTemporalActivacion($tablaBase)
    {
       // $file = $this->request->file('file')->getPathname();
        //$file = str_replace('\\','/',$file);
        $nombre_tabla = 'tmp_'.$tablaBase . Str::random(16);
        
        DB::statement("SET GLOBAL local_infile = `ON`");
        DB::statement("CREATE TABLE {$nombre_tabla} SELECT * FROM {$tablaBase} LIMIT 0");
        //DB::statement("ALTER TABLE {$nombre_tabla} DROP COLUMN `as_svid_id`;");
        
        /*DB::connection()->getPdo()
        ->exec("
            LOAD DATA LOCAL INFILE '{$file}'
            INTO TABLE {$nombre_tabla}
            FIELDS TERMINATED BY ','
            LINES TERMINATED BY '\n'
            IGNORE 0 LINES
            ({$this->campos})");
        */
        DB::statement("SET GLOBAL local_infile = `OFF`");
        
        return $nombre_tabla;
    }
    
    protected function cargarATablaTemporal($tablaBase)
    {
        $file = $this->request->file('file')->getPathname();
        $file = str_replace('\\','/',$file);
        $nombre_tabla = 'tmp_'.$tablaBase . Str::random(16);

        DB::statement("SET GLOBAL local_infile = `ON`");
        DB::statement("CREATE TABLE {$nombre_tabla} SELECT * FROM {$tablaBase} LIMIT 0");
        //DB::statement("ALTER TABLE {$nombre_tabla} DROP COLUMN `as_svid_id`;");

        DB::connection()->getPdo()
            ->exec("
            LOAD DATA LOCAL INFILE '{$file}'
            INTO TABLE {$nombre_tabla}
            FIELDS TERMINATED BY ','
            LINES TERMINATED BY '\n'
            IGNORE 0 LINES
            ({$this->campos})");

        DB::statement("SET GLOBAL local_infile = `OFF`");

        return $nombre_tabla;
    }

    protected function cargarATabla($tabla)
    {
        $file = $this->request->file('file')->getPathname();
        $file = str_replace('\\','/',$file);

        $fileName = $this->request->file('file')->getClientOriginalName();

        $fechaProceso = $this->getFechaProceso( $fileName );

        $respuesta = RespuestaDBUA::create([
            'nombreArchivo' => $this->request->file('file')->getClientOriginalName(),
            'fechaProceso'  => $fechaProceso,
            'gs_usuario_id' => Auth::user()->id,
            'fS' => today()->toDateString()
        ]);
        DB::statement("SET GLOBAL local_infile = `ON`");

        DB::connection()->getPdo()
            ->exec("
            LOAD DATA LOCAL INFILE '{$file}'
            INTO TABLE {$tabla}
            FIELDS TERMINATED BY ','
            LINES TERMINATED BY '\r\n'
            IGNORE 0 LINES
            ({$this->campos}) set as_svid_id = '{$respuesta->id}'");

        DB::statement("SET GLOBAL local_infile = `OFF`");

        return $respuesta;
    }

    public function getRespuestas()
    {
        return $this->respuesta;
    }

    protected function createIndiceIdentificacion(string $tabla_temporal): void
    {
        DB::statement("ALTER TABLE {$tabla_temporal} ADD PRIMARY KEY (tipoIdentificacion, identificacion)");
    }

    protected function validarDuplicados(string $tabla_temporal)
    {
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''))");
        $afiliadosDuplicados = DB::select("
            SELECT a.tipoIdentificacion, a.identificacion, concat_ws(' ',a.primerNombre,a.segundoNombre,a.primerApellido,a.segundoApellido) nombre_completo
            FROM {$tabla_temporal} AS a
            GROUP BY tipoIdentificacion,identificacion
            HAVING     count(*) > 1
        ");
        if(!$afiliadosDuplicados){
            return;
        }
        array_push($this->respuesta,[
            'tipo' => 'error',
            'titulo' => 'Afiliados que estan duplicados en el archivo',
            'afiliados' => $afiliadosDuplicados]);
    }

    protected function validarAfiliadoExiste($tabla_temporal)
    {
        $afiliadosInexistentes = DB::select("
                    SELECT a.tipoIdentificacion as tipo_identificacion, a.identificacion, concat_ws(' ',a.primerNombre,a.segundoNombre,a.primerApellido,a.segundoApellido) nombre_completo 
                    FROM {$tabla_temporal} AS a
                    LEFT JOIN as_afiliados  AS b ON b.tipo_identificacion=a.tipoIdentificacion AND b.identificacion=a.identificacion
                    WHERE b.id IS null");

        if(!$afiliadosInexistentes){
            return;
        }
        array_push($this->respuesta,[
            'tipo' => 'error',
            'titulo' => 'Afiliados que no existen en la base de datos',
            'afiliados' => $afiliadosInexistentes
        ]);
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
        if(!$fallecidos){
            return;
        }
        array_push($this->respuesta,[
            'tipo' => 'error',
            'titulo' => 'Afiliados que se encuentran reportados como fallecidos',
            'afiliados' => $fallecidos
        ]);
    }

    /**
     * @param $fileName
     * @return string
     */
    protected function getFechaProceso($fileName): string
    {
        if(substr($fileName,0,11) == 'AUTOMATICOS'){
            $yearProceso = substr( $fileName , -11 , -7 );

            $mesProceso = substr( $fileName , -13 , -11 );

            $diaProceso = substr( $fileName , -15 , -13 );

            $fechaProceso = "{$yearProceso}-{$mesProceso}-{$diaProceso}";

            return $fechaProceso;
        }

        $yearProceso = substr( $fileName , 12 , 4 );

        $mesProceso = substr( $fileName , 10 , 2 );

        $diaProceso = substr( $fileName , 8 , 2 );

        $fechaProceso = "{$yearProceso}-{$mesProceso}-{$diaProceso}";

        return $fechaProceso;
    }

}