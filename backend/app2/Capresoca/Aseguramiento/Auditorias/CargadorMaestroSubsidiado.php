<?php
/**
 * Created by PhpStorm.
 * User: ojo
 * Date: 2019-03-20
 * Time: 15:07
 */

namespace App\Capresoca\Aseguramiento\Auditorias;

use App\Models\Aseguramiento\AsMsubsidiado;
use App\Models\Temporales\TempMsubsidiado;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CargadorMaestroSubsidiado
{
    protected $nombre;
    protected $ruta;
    protected $campos = [
        'codigo_bdua',
        'codigo_eps',
        'tipo_identificacion_cabeza',
        'identificacion_cabeza',
        'tipo_identificacion',
        'numero_identificacion',
        'primer_apellido',
        'segundo_apellido',
        'primer_nombre',
        'segundo_nombre',
        'fecha_nacimiento',
        'sexo',	'tipo_afiliado',
        'parentesco',
        'grupo_poblacional',
        'nivel_sisben',
        'ficha_sisben',
        'condicion_beneficiario',
        'codigo_departamento',
        'codigo_municipio',
        'zona',
        'fecha_afiliacion_sistema',
        'fecha_afiliacion_eps',
        'numero_contrato',
        'fecha_inicio_contrato',
        'tipo_contrato',
        'pertenecia_etnica',
        'mod_subsidio',
        'estado',
        'fecha_novedad',
        'fecha_desconocida'

    ];
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
            $fecha = substr($this->nombre, 10,8);
            $existe = AsMsubsidiado::where('nombre_archivo', $this->nombre)->where('fecha_archivo', $fecha )->get();
            if($existe->count() > 0){
                return (object)[
                    'validado' => false,
                    'errores' => "Ya existe un archivo en la base de datos con el mismo nombre y fecha"
                ];
            }
            $this->fileContent = file($this->ruta);

            $validarColumnas = $this->validarColumnas();
            if($validarColumnas->validado){
                $validarLineas = $this->validarLineas();
                if($validarLineas->validado){
                    $this->validado = true;
                    return  (object)[
                        'validado' => true
                    ];

                }else{
                    return  (object)[
                        'validado' => false,
                        'errores' => $validarLineas->errores
                    ];

                }
            }else{
                return  (object)[
                    'validado' => false,
                    'errores' => $validarColumnas->errores
                ];

            }


        }catch (\Exception $exception){
            return  (object)[
                'validado' => false,
                'errores' => $exception->getMessage()
            ];
        }
    }

     protected function validarLineas(){
        $reglas = [
            'codigo_bdua' => 'required',
            'codigo_eps'  => 'required',
            'tipo_identificacion_cabeza'  => 'required',
            'identificacion_cabeza'  => 'required',
            'tipo_identificacion'  => 'required',
            'numero_identificacion'  => 'required',
            'primer_apellido'  => 'required',
            'primer_nombre'  => 'required',
            'fecha_nacimiento'  => 'required | date_format:d/m/Y',
            'sexo',	'tipo_afiliado'  => 'required',
            //'grupo_poblacional'  => 'required',
            //'nivel_sisben'  => 'required',
            'codigo_departamento'  => 'required',
            'codigo_municipio'  => 'required',
            'zona'  => 'required',
            'fecha_afiliacion_sistema'  => 'required | date_format:d/m/Y',
            'fecha_afiliacion_eps'  => 'required | date_format:d/m/Y',
            'estado'  => 'required',
            'fecha_novedad'  => 'required'
        ];
        $errores = [];

        foreach ($this->fileContent as $key => $linea){
            $lineaArray = explode(',', utf8_encode($linea));
            $itemData = [];
            foreach ($lineaArray as $itemKey => $value){
                $itemData[$this->campos[$itemKey]] = $value;
            }
            $validator = Validator::make($itemData, $reglas);
            if ($validator->fails()){
                $errores["Linea_".($key + 1)] = $validator->errors();
            }
        }

        if (sizeof($errores) > 0) {
            return (object)[
                'validado' => false,
                'errores' => $errores
            ];
        }else{
            return  (object)[
                'validado' => true
            ];
        }

    }

    protected function validarColumnas(){

        $errores = [];
        foreach ($this->fileContent as $key => $linea){
            $lineaArray = explode(',', $linea);
            if(sizeof($lineaArray) != 31){
                array_push($errores, "La cantidad de columnas deben ser 31, columnas encontradas: ".sizeof($lineaArray).". Linea ".($key + 1));
            }
            $this->lineas++;
        }
        if (sizeof($errores) > 0){
            return (object)[
                'validado' => false,
                'errores' => $errores
            ];
        }else{
            return  (object)[
                'validado' => true
            ];
        }
    }

    public function store()
    {
        if($this->validado){
            $fecha = substr($this->nombre, 10,8);
            $data = file_get_contents($this->ruta);
            $data = mb_convert_encoding($data, 'UTF-8', mb_detect_encoding($data, $this->encodingList));
            //file_put_contents(Storage::disk('local')->path, $data);
            file_put_contents($this->ruta, $data);

            try{
                DB::beginTransaction();
                $ms = new AsMsubsidiado();
                $ms->fecha_archivo = $fecha;
                $ms->nombre_archivo = $this->nombre;
                $ms->numero_filas = $this->lineas;
                $ms->gs_usuario_id =  Auth::user()->id;
                $ms->save();
                TempMsubsidiado::truncate();
                //$ruta = Storage::disk('local')->url($this->ruta);
                DB::select('SET GLOBAL local_infile = `ON`');
                DB::connection()->getPdo()
                    ->exec("
                LOAD DATA LOCAL INFILE '{$this->ruta}'
                INTO TABLE temp_msubsidiados
                FIELDS TERMINATED BY ','
                (
                    `codigo_bdua`,
                    `codigo_eps`,
                    `tipo_identificacion_cabeza`,
                    `identificacion_cabeza`,
                    `tipo_identificacion`,
                    `numero_identificacion`,
                    `primer_apellido`,
                    `segundo_apellido`,
                    `primer_nombre`,
                    `segundo_nombre`,
                    `fecha_nacimiento`,
                    `sexo`,	`tipo_afiliado`,
                    `parentesco`,
                    `grupo_poblacional`,
                    `nivel_sisben`,
                    `ficha_sisben`,
                    `condicion_beneficiario`,
                    `codigo_departamento`,
                    `codigo_municipio`,
                    `zona`,
                    `fecha_afiliacion_sistema`,
                    `fecha_afiliacion_eps`,
                    `numero_contrato`,
                    `fecha_inicio_contrato`,
                    `tipo_contrato`,
                    `pertenecia_etnica`,
                    `mod_subsidio`,
                    `estado`,
                    `fecha_novedad`,
                    `fecha_desconocida`
                ) 
                SET as_msubsidiado_id = '{$ms->id}'
                ");
                DB::select('SET GLOBAL local_infile = `OFF`');
                DB::commit();
                $this->deleteFile();
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
