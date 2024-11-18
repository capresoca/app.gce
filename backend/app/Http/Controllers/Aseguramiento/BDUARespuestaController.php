<?php

namespace App\Http\Controllers\Aseguramiento;

use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorRespuestas;
use App\Http\Requests\Aseguramiento\BDUARespuestaRequest;
use App\Http\Resources\Aseguramiento\BDUARespuestaResource;
use App\Models\Aseguramiento\AsBduaproceso;
use App\Models\Aseguramiento\AsBduarespuesta;
use App\Models\Aseguramiento\AsTipbduaarchivo;
use App\Models\Aseguramiento\RespuestaDBUA;
use App\Traits\ToolsTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;
use Nexmo\Response;
use App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores\ProcesaBDUA;
use App\Models\Enums\ETipoBdua;
use Illuminate\Support\Facades\Log;
use App\Models\TbVigenciaTraslado;
use App\Models\Enums\ESiNo;
use App\Models\Enums\ETipoNovedad;

class BDUARespuestaController extends Controller
{
    public function index()
    {
        if (Input::get('per_page')) {
            return BDUARespuestaResource::collection(
                AsBduarespuesta::with('archivo')->pimp()
                    ->orderBy('updated_at', 'desc')
                    ->paginate(Input::get('per_page'))
            );
        }

        return BDUARespuestaResource::collection(
            AsBduarespuesta::with('archivo')
                ->pimp()
                ->orderBy('updated_at', 'desc')
                ->get()
        );

    }

    public function respuestas()
    {
        if (Input::get('per_page')) {
            return Resource::collection(
                RespuestaDBUA::pimp()->orderBy('updated_at', 'desc')
                    ->with('usuario')
                    ->paginate(Input::get('per_page'))
            );
        }

        return Resource::collection(
            RespuestaDBUA::pimp()->with('usuario')->orderBy('updated_at', 'desc')
                ->get()
        );
    }

    public function store(BDUARespuestaRequest $request)
    {
        try {
            $proceso = AsBduaproceso::findOrFail($request->as_bduaproceso_id);
            $procesador = new ProcesadorRespuestas($request->file('files'), $proceso);
            return $procesador->procesarArchivos();
        } catch (\Exception $e) {
            return $e;
        }

    }

    public function cargarRespuesta(Request $request)
    {
        Log::info('ENTRA AL METODO');
        
        $fileUploaded       = $request->file('file');
        $tipoV              = ETipoBdua::getResultadoDescripcion($request->tipo);
        
        $bduaProcesar       = new ProcesaBDUA();
        $bduaProcesar->setDescripcion($request->descripcion);
        $bduaProcesar->setFecha($request->fecha);
        $bduaProcesar->setArchivo($request->archivo);
        $bduaProcesar->setFinaliza($request->finaliza);
        $bduaProcesar->setVigencia($request->vigencia);
        $bduaProcesar->setTipo($tipoV->getId());
        
        
        
        if($request->file('file')!=null) {
            $bduaProcesar->setUrlArchivo($request->file('file')->getClientOriginalName());
        }

        $hayGuardado = $bduaProcesar->validar();
        Log::info('PASA VALIDACION SUBIDO');
        if(!empty($hayGuardado)) {
            return response()->json([
                'message' => 'Ya existe un archivo como finalizado',
                'errors' => '',
            ], 500);
        }
        
        if($tipoV->getId()==ETipoBdua::getIndice(ETipoBdua::S3)->getId() || $tipoV->getId()==ETipoBdua::getIndice(ETipoBdua::R3)->getId()) {
            $vigencia       = TbVigenciaTraslado::where('fecha_inicio', '<=', date('Y-m-d'))->where('fecha_fin','>=',date('Y-m-d'))
            ->where('sw_abierto','=',ESiNo::getIndice(ESiNo::SI)->getId())
            ->where('tipo','=',ETipoNovedad::getIndice(ETipoNovedad::TRASLADO)->getId())->first();
            
            if($vigencia==null || empty($vigencia)) {
                $errores[]      = 'No existe vigencia para crear la novedad y procesarla';
                return response()->json([
                    'message' => 'No existe vigencia para crear la novedad y procesarla',
                    'errors' => '',
                ], 500);
            } else {
                $bduaProcesar->setVigenciaNueva($vigencia);
            }
        }
        
        if($fileUploaded!=NULL) {
            Log::info('Entra cargue archivo');
            
            //$fileName           = request()->file->getClientOriginalName();
            $arrayDatos         = array();
            $fp                 = fopen($fileUploaded,"r");
            
            $errores            = array();
            $cantidad           = 0;
            while (($datos = fgetcsv($fp)) !== FALSE) {
                $cantidad++;
                //$datos          = explode(',', $datos1);
                $arrayDatos[]   = $datos;
                //Log::info('Cantidad Datos: ',array(count($datos)));
                if($tipoV->getId()==ETipoBdua::getIndice(ETipoBdua::NS_VAL)->getId() || $tipoV->getId()==ETipoBdua::getIndice(ETipoBdua::NC_VAL)->getId()) {
                    if(count($datos) != 19) {
                        $errores[]      = 'El archivo no tiene la estructura de para ser valido';
                        break;
                    }
                } elseif($tipoV->getId()==ETipoBdua::getIndice(ETipoBdua::NS_NEG)->getId()) {
                    if(count($datos) != 19) {
                        $errores[]      = 'El archivo no tiene la estructura de para ser valido';
                        break;
                    }
                } elseif($tipoV->getId()==ETipoBdua::getIndice(ETipoBdua::NC_NEG)->getId()) {
                    if(count($datos) != 21) {
                        $errores[]      = 'El archivo no tiene la estructura de para ser valido';
                        break;
                    }
                } elseif($tipoV->getId()==ETipoBdua::getIndice(ETipoBdua::MS_VAL)->getId()) {
                    if(count($datos) != 21) {
                        $errores[]      = 'El archivo no tiene la estructura de para ser valido';
                        break;
                    }
                } elseif($tipoV->getId()==ETipoBdua::getIndice(ETipoBdua::MC_VAL)->getId()) {
                    if(count($datos) != 23) {
                        $errores[]      = 'El archivo no tiene la estructura de para ser valido';
                        break;
                    }
                } elseif($tipoV->getId()==ETipoBdua::getIndice(ETipoBdua::MS_NEG)->getId() || $tipoV->getId()==ETipoBdua::getIndice(ETipoBdua::MC_NEG)->getId()) {
                
                    if(count($datos) != 17) {
                        $errores[]      = 'El archivo no tiene la estructura de para ser valido';
                        break;
                    }
                }/*elseif($tipoV->getId()==ETipoBdua::getIndice(ETipoBdua::S3)->getId() || $tipoV->getId()==ETipoBdua::getIndice(ETipoBdua::S6)->getId()) {
                    if(count($datos) != 25) {
                        $errores[]      = 'El archivo no tiene la estructura de para ser valido';
                        break;
                    }
                }*/
            }
            //Log::info('Cantidad  filas: ', array($cantidad));
            $bduaProcesar->setCantidadFilas($cantidad);
            
            //return array('registros: '.count($arrayDatos));
            //return array('fila: '.$cantidad);
            fclose($fp);
            Log::info('Errores: ', array($errores));
            if(!empty($errores)) {
                return $errores;
            }
            
            $bduaProcesar->setData($arrayDatos);
            
            Log::info('Inicia Proceso Archivo: ',array($request->tipo));
            //$bduaProcesar->set
           // return array('prueba: '.count($datos));
            if($bduaProcesar->procesar()) {
                return array('todo correcto');
            } else {
                return array('error no manejado');
            }
            //}
        } /*else {
            return array($bduaProcesar->actualizar());
            return array('Actualizado');
        }*/
        
        
        
       /* if(!ToolsTrait::checkCollation($request->file('file')->get(),'UTF-8')){
            return response()->json(['message' => 'Favor convertir a codificación UTF-8'],422);
        }

        $tipExplode = explode('.',$request->tipo);
        $pre = $tipExplode[0];
        $sufijo = null;
        $sufijoArchivo = null;

        if(isset($tipExplode[1])){
            $sufijo = $tipExplode[1];
        }

        $fileName = $request->file('file')->getClientOriginalName();

        if(RespuestaDBUA::where('nombreArchivo',$fileName)->exists()){
            return response()->json(['message' => 'Este archivo ya fue procesado'],422);
        }

        if($sufijo){
            $sufijoArchivo = substr($fileName,-7,-4);
        }

        $prefijoArchivo = substr($fileName,0,strlen($pre));

        if($sufijo != $sufijoArchivo || $prefijoArchivo != $pre){
            return response()->json(['message' => 'El archivo no coicide'],422);
        }

        $tipoArchivo = AsTipbduaarchivo::where(['iniciales' => $prefijoArchivo, 'sufijo' => $sufijo])->first();

        if(!$tipoArchivo){
            return response()->json(['message' => 'Archivo no conocido'],422);
        }

        $procesador = new $tipoArchivo->clase_procesador($request);

        $procesador->procesar();

        return $procesador->getRespuestas();*/
        
       /*if($tipoV->getId()==ETipoBdua::getIndice(ETipoBdua::S1_AUTOMATICO)->getId()) {
        if(!ToolsTrait::checkCollation($request->file('file')->get(),'UTF-8')){
        return response()->json(['message' => 'Favor convertir a codificación UTF-8'],422);
        }
        
        $tipExplode         = explode('.',$request->tipo);
        $pre                = $tipExplode[0];
        $sufijo             = null;
        $sufijoArchivo      = null;
        
        if(isset($tipExplode[1])){
        $sufijo = $tipExplode[1];
        }
        
        $fileName = $request->file('file')->getClientOriginalName();
        
        if(RespuestaDBUA::where('nombreArchivo',$fileName)->exists()){
        return response()->json(['message' => 'Este archivo ya fue procesado'],422);
        }
        
        if($sufijo){
        $sufijoArchivo = substr($fileName,-7,-4);
        }
        
        $prefijoArchivo = substr($fileName,0,strlen($pre));
        
        if($sufijo != $sufijoArchivo || $prefijoArchivo != $pre){
        return response()->json(['message' => 'El archivo no coicide'],422);
        }
        
        $tipoArchivo = AsTipbduaarchivo::where(['iniciales' => $prefijoArchivo, 'sufijo' => $sufijo])->first();
        
        if(!$tipoArchivo){
        return response()->json(['message' => 'Archivo no conocido'],422);
        }
        
        $procesador = new $tipoArchivo->clase_procesador($request);
        
        $procesador->procesar();
        
        return $procesador->getRespuestas();
        } else {*/

    }
    
    

    public function show(AsBduarespuesta $bduarespuesta)
    {
        $bduarespuesta->load('registros.glosas', 'registros.acciones', 'pendientes');
        return new BDUARespuestaResource($bduarespuesta);
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
