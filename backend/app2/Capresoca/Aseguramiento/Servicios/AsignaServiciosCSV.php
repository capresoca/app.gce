<?php
/**
 * Created by PhpStorm.
 * User: Wilfredo
 * Date: 18/02/2020
 * Time: 3:36 PM
 */

namespace App\Capresoca\Aseguramiento\Servicios;


use App\Models\RedServicios\RsAfiliadoServicio;
use App\Models\RedServicios\RsAsignamasivo;
use App\RedServicios\RsServhabilitados;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AsignaServiciosCSV
{
    protected $path;
    protected $respuesta = [];
    protected $servhabilitados = [];
    public function __construct($path,String $servhabilitados)
    {
        $this->path = $path;
        $this->servhabilitados = explode(',',$servhabilitados);
    }

    public function handle()
    {
        $tablaTemporal = $this->createTemp();
        $this->cargarATablaTemporal($tablaTemporal);
        $this->validarAfiliadoExiste($tablaTemporal);

        $this->createIndiceIdentificacion($tablaTemporal);

        if(count($this->respuesta)){
            DB::statement("DROP TABLE {$tablaTemporal}");
            return;
        };
        $ids = $this->getIds($tablaTemporal);

        $insert = $this->createInsert($ids);

        foreach(array_chunk($insert,1000) as $insertChunked){
            RsAfiliadoServicio::insert($insertChunked);
        }
    }

    public function getRespuesa()
    {
        return $this->respuesta;
    }

    protected function createTemp()
    {
        $nombreTabla = 'tmp_idsafi'.Str::random(8);
        DB::statement( "CREATE TABLE {$nombreTabla} (
            `tipoIdentificacion` CHAR(2) NULL,
            `identificacion` VARCHAR(20) NULL
        ) COLLATE='utf8_general_ci' ENGINE=InnoDB" );

        return $nombreTabla;
    }

    protected function validarAfiliadoExiste($tabla_temporal)
    {
        $afiliadosInexistentes = DB::select("
                    SELECT a.tipoIdentificacion as tipo_identificacion, a.identificacion
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

    protected function cargarATablaTemporal($tablaTemporal)
    {
        $file = str_replace('\\','/',$this->path);
        DB::statement("SET GLOBAL local_infile = `ON`");
        DB::connection()->getPdo()
            ->exec("
            LOAD DATA LOCAL INFILE '{$file}'
            INTO TABLE {$tablaTemporal}
            FIELDS TERMINATED BY ','
            LINES TERMINATED BY '\r\n'
            IGNORE 0 LINES
            (tipoIdentificacion,identificacion)");

        DB::statement("SET GLOBAL local_infile = `OFF`");

    }

    protected function createIndiceIdentificacion(string $tabla_temporal): void
    {
        DB::statement("ALTER TABLE {$tabla_temporal} ADD PRIMARY KEY (tipoIdentificacion, identificacion)");
    }

    private function createInsert($ids)
    {
        $insert = [];
        $asigna_masivo = RsAsignamasivo::create(['tipo_proceso' => 'Asignar servicios']);
        foreach( $ids as $id ) {
            foreach( $this->servhabilitados as $servhabilitado ) {
                array_push($insert,[
                    'as_afiliado_id' => $id->id,
                    'rs_servhabilitado_id' => $servhabilitado,
                    'rs_asignamasivo_id' => $asigna_masivo->id
                ]);
            }
        }

        return $insert;
    }

    private function getIds($tablaTemporal)
    {
        return DB::select("SELECT b.id
            FROM {$tablaTemporal} AS a
            left JOIN as_afiliados AS b ON a.identificacion = b.identificacion
            where b.id is not null");
    }


}