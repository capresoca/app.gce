<?php


namespace App\Capresoca\Aseguramiento\ProcesosBDUA;

use App\Models\Aseguramiento\Procesos\AsS1vid;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProcesadorS1
{
    protected $archivosBDUA;
    protected $nombreArchivo;
    protected $campos= "codeps,
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

    public function __construct($archivosBDUA)
    {
        $this->archivosBDUA = $archivosBDUA;
        $this->nombreArchivo = $archivosBDUA->nombreArchivo;


    }

    public function procesar () {
        $this->procesarTipoArchivo();
    }

    private function procesarTipoArchivo()
    {
        try {
            $ruta = $this->archivosBDUA->ruta_archivo;

//            $ruta = Storage::url($this->csvPath);
            Log::info('key2 '.$ruta);
            $nombre_tabla = Str::random(16).'S1Automatico';
            DB::statement("SET GLOBAL local_infile = `ON`");
            DB::statement("CREATE TABLE {$nombre_tabla} SELECT * FROM as_s1autvals LIMIT 0");
            DB::statement("ALTER TABLE {$nombre_tabla} ADD PRIMARY KEY (tipoIdentificacion, identificacion)");
            $data = DB::connection()->getPdo()
                ->exec("
            LOAD DATA LOW_PRIORITY INFILE '{$ruta}'
            INTO TABLE {$nombre_tabla}
            FIELDS TERMINATED BY ','
            LINES TERMINATED BY '\r\n'
            IGNORE 0 LINES
            ({$this->campos})");
            Log::info('key1 '.$data);
            DB::statement("SET GLOBAL local_infile = `OFF`");
//            DB::select('SET GLOBAL local_infile = `OFF`');
//            DB::commit();
        } catch (\Exception $e) {
            Log::info('Error ejecutar ED DB: '.$e->getMessage());
        }
        //        $contents = Storage::get($this->archivosBDUA->ruta_archivo);
//        dd($contents);
//        $openFile = fopen($content,'r');
//        if (!$openFile) throw new \Exception("No hasido posible leer el archivo $this->nombreArchivo");
//
//        while (!feof($openFile)) {
//            $prueba = fgets($openFile);
//            dd($prueba);
//        }
//        $nombre = $this->validarArchivo($this->nombreArchivo);
//        $nombre = substr($this->nombreArchivo, 12,2);
//        dd($nombre);
//        if (!$this->validarArchivo($tipo)) {
//            throw new \Exception('El archivo no es valido para la validación.');
//        }
//        dd('tu',$tipo);
//        $file= $this->archivosBDUA;

//        switch ($this->) {
//            case 'AUTOMATICOS-S1':
//
//                break;
//            default: '';
//        }

    }

}