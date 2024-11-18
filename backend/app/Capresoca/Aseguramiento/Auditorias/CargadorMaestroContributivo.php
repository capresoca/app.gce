<?php


namespace App\Capresoca\Aseguramiento\Auditorias;


use App\Models\Aseguramiento\AsMsubsidiado;
use App\Models\General\GnEmpresa;
use App\Models\Temporales\TempMcontributivo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CargadorMaestroContributivo
{
    protected $nombre;
    protected $ruta;
    protected $fileContent;
    protected $validado = false;
    protected $lineas = 0;
    protected $data;
    private $encodingList = array(
        'UTF-8', 'ASCII',
        'ISO-8859-1', 'ISO-8859-2', 'ISO-8859-3', 'ISO-8859-4', 'ISO-8859-5',
        'ISO-8859-6', 'ISO-8859-7', 'ISO-8859-8', 'ISO-8859-9', 'ISO-8859-10',
        'ISO-8859-13', 'ISO-8859-14', 'ISO-8859-15', 'ISO-8859-16',
        'Windows-1251', 'Windows-1252', 'Windows-1254',
    );

    public function __construct($nombre, $ruta)
    {
        $this->nombre = $nombre;
        $this->ruta = $ruta;

    }

    public function validar(){
        try{
            if(substr($this->nombre, 0,8) != 'CAEPS025'){
                return (object)[
                    'validado' => false,
                    'errores' => "El nombre del archivo no concuerda con la norma"
                ];
            }
            $existe = AsMsubsidiado::where('nombre_archivo', $this->nombre)->where('fecha_archivo', substr($this->nombre, 8,8) )->get();
            if($existe->count() > 0){
                return (object)[
                    'validado' => false,
                    'errores' => "Ya existe un archivo en la base de datos con el mismo nombre y fecha"
                ];
            }
            $this->validado = true;
            return  (object)[
                'validado' => true,
            ];
        }catch (\Exception $exception){
            return  (object)[
                'validado' => false,
                'errores' => $exception->getMessage()
            ];
        }
    }

    public function store()
    {
        if($this->validado){
            $data = file_get_contents($this->ruta);
            $data = mb_convert_encoding($data, 'UTF-8', mb_detect_encoding($data, $this->encodingList));
            //file_put_contents(Storage::disk('local')->path, $data);
            file_put_contents($this->ruta, $data);

            /*$doc = new DOMDocument();
            $doc->preserveWhiteSpace = false;
            $doc->formatOutput = true;
            $doc->loadXML($data);
            $doc->save($this->ruta);*/
            try{
                DB::beginTransaction();
                $ms = new AsMsubsidiado();
                $ms->fecha_archivo = substr($this->nombre, 8,8);
                $ms->nombre_archivo = $this->nombre;
                $ms->numero_filas = $this->lineas;
                $ms->tipo = 'Contributivo';
                $ms->gs_usuario_id =  Auth::user()->id;
                $ms->save();
                TempMcontributivo::truncate();
                //$ruta = Storage::disk('local')->url($this->ruta);
                DB::select('SET GLOBAL local_infile = `ON`');
                DB::connection()->getPdo()
                    ->exec("
                LOAD XML LOCAL INFILE '{$this->ruta}'
                INTO TABLE temp_mcontributivos
                ROWS IDENTIFIED BY '\<Consultados\>' 
                (
                IDENTIFICADOR,
                SERIAL,
                TIPO_IDENTIFICACION,
                NUMERO_IDENTIFICACION,
                PRIMER_NOMBRE,
                SEGUNDO_NOMBRE,
                PRIMER_APELLIDO,
                SEGUNDO_APELLIDO,
                NACIMIENTO_FECHA,
                DEPTO,
                MUNI,
                ESTADO,
                ENT_ID,
                ENTIDAD,
                REGIMEN,
                FECHA_CONTRATO,
                FECHA_FIN_CONTRATO,
                AFILIACION_ENTIDAD_FECHA,
                ESTADO_FECHA,
                TIPO_AFILIADO,
                FECHA_AFILIACION_EFECTIVA,
                cantidad_grp_fml
                )
                SET as_msubsidiado_id = '{$ms->id}'
                ");
                DB::select('SET GLOBAL local_infile = `OFF`');
                DB::commit();
                $this->deleteFile();
                $empresa = GnEmpresa::first();
                TempMcontributivo::where('ENT_ID',"!=", $empresa->codhabilitacion_contrib)->delete();
                $ms->numero_filas = TempMcontributivo::all()->count();
                $ms->save();
                return (object)[
                    'creado' => true,
                    'ms' => $ms
                ];
            }catch (\Exception $exception){
                DB::rollBack();
                return (object)[
                    'creado' => false,
                    'errores' => $exception->getMessage()
                ];
            }
        }else{
            return (object)[
                'creado' => false,
                'errores' => "El archivo no ha sido validado"
            ];
        }

    }
    protected function deleteFile(){
        if(file_exists($this->ruta)){
            unlink($this->ruta);
        }
    }

}