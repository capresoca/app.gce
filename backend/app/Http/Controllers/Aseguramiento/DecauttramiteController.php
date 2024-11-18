<?php

namespace App\Http\Controllers\Aseguramiento;

use App\Http\Requests\Aseguramiento\DecautramiteRequest;
use App\Models\Aseguramiento\AsDecauttramite;
use App\Models\Aseguramiento\AsDeclaracione;
use App\Models\Aseguramiento\AsFormafiliacione;
use App\Models\Aseguramiento\AsFormtrasmov;
use App\Models\Aseguramiento\AsTramite;
use App\Models\General\GnArchivo;
use App\Traits\ArchivoTrait;
use Aws\Exception\AwsException;
use Aws\S3\Exception\S3Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class DecauttramiteController extends Controller
{

    public function index()
    {
        return AsDecauttramite::all();
    }


    public function store(DecautramiteRequest $request)
    {
        try {
            $url = null;
            $ruta_anterior = null;
            $decauttramite = AsDecauttramite::find($request->id);
            DB::BeginTransaction();
            if($request->hasFile('anexo')){

                if($decauttramite->anexo){
                    $ruta_anterior = $decauttramite->anexo->ruta;
                    $decauttramite->anexo->delete();
                }

                $url = $request->anexo->store('Aseguramiento/decauttramites');

                $archivo = GnArchivo::create(
                    [
                        'ruta' => $url,
                        'nombre' => $request->nombre_archivo,
                        'size' => $request->anexo->getClientSize(),
                        'extension' => $request->anexo->extension()
                    ]
                );

                $decauttramite->gn_archivo_id = $archivo->id;
            } else {
                if($decauttramite->anexo){
                    $ruta_anterior = $decauttramite->anexo->ruta;
                    $decauttramite->anexo->delete();
                }
            }

            $decauttramite->declara = $request->declara;
            $decauttramite->save();

            if($ruta_anterior)
                Storage::delete($ruta_anterior);

            DB::commit();
            return $decauttramite->load('declaracion','anexo');

        }catch (S3Exception $e) {
            DB::rollBack();
            return response()->json(
                [
                    'message' => 'No se pudo almacenar el archivo'
                ],
                500
            );
        } catch (AwsException $e) {
            DB::rollBack();
            return response()->json(
                [
                    'message' => 'No se pudo almacenar el archivo'
                ],
                500
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(
                [
                    'message' => 'Ocurrio un error en el servidor',
                    'error' => $e->getMessage().' '.$e->getLine()
                ],
                500
            );
        }
    }


    public function show(AsDecauttramite $decautramite)
    {
        return ArchivoTrait::download($decautramite->anexo->ruta, $decautramite->anexo->nombre);
    }


    public function update(Request $request, AsDecauttramite $decautramite)
    {
        if($request->hasFile('anexo'))
            $url = $request->anexo->store('decauttramites');

        $decautramite->fill($request->except('anexo'));
        $decautramite->anexo = $url;
        $decautramite->save();

    }

    public function creaDeclaraciones(){

        $key = Input::get('key');
        $id_form = Input::get('form_id');


        foreach (AsDeclaracione::all() as $declaracion){
            $decautramite_existente = AsDecauttramite::where($key,$id_form)
                                        ->where('as_declaracione_id', $declaracion->id)
                                        ->get();

            if(!$decautramite_existente->count()){
                $decautramite = new AsDecauttramite();
                $decautramite[$key] = $id_form;
                $decautramite->as_declaracione_id = $declaracion->id;
                $decautramite->save();
            }

        }
        if($key === 'as_formtrasmov_id'){
            $form = AsFormtrasmov::find($id_form);
        }
        else{
            $form = AsFormafiliacione::find($id_form);
        }

        return $form->declaraciones->load('declaracion','anexo');
    }
}
