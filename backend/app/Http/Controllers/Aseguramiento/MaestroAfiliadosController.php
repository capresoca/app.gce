<?php

namespace App\Http\Controllers\Aseguramiento;

use App\Capresoca\Aseguramiento\Auditorias\AuditorMaestroContributivo;
use App\Capresoca\Aseguramiento\Auditorias\AuditorMaestroSubsidiado;
use App\Capresoca\Aseguramiento\Auditorias\CargadorMaestroContributivo;
use App\Capresoca\Aseguramiento\Auditorias\CargadorMaestroSubsidiado;
use App\Http\Resources\Aseguramiento\AsAfiliadoResource;
use App\Http\Resources\Aseguramiento\AsMsubsidiadoResource;
use App\Models\Aseguramiento\AsMsubsidiado;
use App\Models\Presupuesto\PrConcepto;
use App\Models\Presupuesto\PrDetstrgasto;
use App\Models\Presupuesto\PrStrgasto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class MaestroAfiliadosController extends Controller
{

    public function index(){
        if (Input::get('per_page')) {
            return AsMsubsidiadoResource::collection(
                AsMsubsidiado::with('usuario')
                    ->pimp()
                    ->orderBy('updated_at', 'desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return AsMsubsidiadoResource::collection(AsMsubsidiado::with('usuario')->pimp()->orderBy('updated_at', 'desc')->get());
    }


    public function store(Request $request){

        try {
            $archivo = $request->file('archivo');

            $nombre = $archivo->getClientOriginalName();
            $realName = explode('.',$nombre)[0];
            $ruta = Storage::disk('local')->put('/', $archivo);
            $path = Storage::disk('local')->path($ruta);
            $zip = new \ZipArchive;
            $zip->open($path);
            $zip->extractTo(Storage::disk('local')->path('/'));
            $zip->close();
            if($request->tipo == 'Subsidiado'){
                $newPath = Storage::disk('local')->path($realName.'.TXT');
                // return $newPath;
                $validarMS = new CargadorMaestroSubsidiado($nombre, $newPath);
                $validador = $validarMS->validar();
                if($validador->validado){
                    $guardar = $validarMS->store();
                    if($guardar->creado){
                        Storage::disk('local')->delete($ruta);
                        $auditor = new AuditorMaestroSubsidiado($guardar->ms->id);
                        $log = $auditor->run();
                        return response()->json([
                            'proceso' => $guardar->ms,
                            'log' => $log,
                        ],200);
                    }else{
                        return response()->json( $guardar->errores,422);
                    }
                }else{
                    return response()->json( $validador->errores,422);
                }
            }else{
                $newPath = Storage::disk('local')->path($realName.'_Afiliados.xml');
                $cargadorContributivo = new CargadorMaestroContributivo($nombre, $newPath);
                $validador = $cargadorContributivo->validar();
                if($validador->validado){
                    $cargar = $cargadorContributivo->store();
                    if ($cargar->creado){
                        Storage::disk('local')->delete($ruta);
                        $auditorMC = new AuditorMaestroContributivo($cargar->ms->id);
                        $log = $auditorMC->run();
                        return response()->json([
                            'proceso' => $cargar->ms,
                            'log' => $log,
                        ],200);
                    }else{
                        return response()->json( $cargar->errores,422);
                    }
                }else{
                    return response()->json( $validador->errores,422);
                }

            }




        }catch (\Exception $exception){
            return $exception;
        }
    }

    public function buscarSiExisteArchivo ($nombre)
    {
        try {
            $archivo_ms = AsMsubsidiado::where('nombre_archivo',$nombre)->first();
            if (isset($archivo_ms)) return response()->json(['exists' => true,'msg'=> 'Ya existe el archivo en los registros.']);
            return response()->json(
                [
                    'exists' =>  false,
                    'msg' => 'Cargando el archivo MS.'
                ]
            );

        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception,
                'msg' => 'Error en el servidor al validar la existencia del archivo.'
            ],500);
        }
    }



}
