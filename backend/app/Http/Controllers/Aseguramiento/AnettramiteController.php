<?php

namespace App\Http\Controllers\Aseguramiento;

use App\Http\Requests\Aseguramiento\AnettramiteRequest;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsAnetramite;
use App\Models\General\GnArchivo;
use App\Traits\ArchivoTrait;
use Aws\Exception\AwsException;
use Aws\S3\Exception\S3Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AnettramiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AnettramiteRequest $request)
    {
        try {

            $url = null;
            $ruta_aterior = null;
            DB::beginTransaction();
            $afiliado = AsAfiliado::find($request->as_afiliado_id);
            $request['gn_tipo_doc_identidades_id'] = $afiliado->gn_tipdocidentidad_id;
            if(!$request->id){
                $anetramite = AsAnetramite::create(
                    $request->except(['anexo','nombre_archivo', 'id'])
                );
            }else{
                $anetramite = AsAnetramite::find($request['id']);
                $anetramite->fill($request->except(['anexo','nombre_archivo']));
            }

            if($request->hasFile('anexo')){

                $url = $request->anexo->store('Aseguramiento/anetramites');

                $archivo = GnArchivo::create(
                    [
                        'ruta' => $url,
                        'nombre' => $request->nombre_archivo,
                        'size' => $request->anexo->getClientSize(),
                        'extension' => $request->anexo->extension()
                    ]
                );

                $anetramite->gn_archivo_id = $archivo->id;
                $anetramite->save();

            }
            DB::commit();
            return response()->json(
                $anetramite->load('anexo'), 201
            );
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


    public function show(AsAnetramite $anetramite)
    {
        return ArchivoTrait::download($anetramite->anexo->ruta, $anetramite->anexo->nombre);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }


    public function destroy(AsAnetramite $anetramite)
    {
        try{

            if(Storage::delete($anetramite->anexo->ruta)){
                DB::beginTransaction();
                $anetramite->anexo()->delete();
                $anetramite->delete();
                DB::commit();
            }
            return response()->json(
                [
                    'message' => 'Se eliminó el anexo'
                ], 200
            );
        }catch (S3Exception $e) {
            DB::rollBack();
            return response()->json(
                [
                    'message' => 'No se pudo eliminar el archivo'
                ],
                500
            );
        } catch (AwsException $e) {
            DB::rollBack();
            return response()->json(
                [
                    'message' => 'No se pudo eliminar el archivo'
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

}
