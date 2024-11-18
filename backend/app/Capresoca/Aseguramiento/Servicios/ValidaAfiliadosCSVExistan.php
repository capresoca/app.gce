<?php
/**
 * Created by PhpStorm.
 * User: Wilfredo
 * Date: 20/02/2020
 * Time: 9:17 AM
 */

namespace App\Capresoca\Aseguramiento\Servicios;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ValidaAfiliadosCSVExistan
{
    protected $path;
    protected $respuesta = [];
    public function __construct($path)
    {
        $this->path = $path;
    }

    public function handle()
    {
        try{
            $tablaTemporal = $this->createTemp();

            $this->cargarATablaTemporal($tablaTemporal);

            $this->createIndiceIdentificacion($tablaTemporal);

            $this->validarAfiliadoExiste($tablaTemporal);

            DB::statement("DROP TABLE {$tablaTemporal}");

        }catch(\Exception $exception){
            array_push($this->respuesta,[
                'tipo' => 'error',
                'titulo' => 'Se presento un error con el archivo: '.$exception->getMessage(),
                'afiliados' => ''
            ]);
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
}