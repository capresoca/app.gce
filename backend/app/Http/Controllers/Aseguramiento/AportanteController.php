<?php

namespace App\Http\Controllers\Aseguramiento;

use App\Capresoca\LeerCsv;
use App\Http\Requests\Aseguramiento\AportanteRequest;
use App\Jobs\ProcesarAportantes;
use App\Models\Aseguramiento\AsAfiliadoPagador;
use App\Models\Aseguramiento\AsFormtrasmov;
use App\Models\Aseguramiento\AsPagadore;
use App\Models\Aseguramiento\AsTipaportante;
use App\Models\General\GnMunicipio;
use App\Models\Niif\GnTercero;
use App\Models\Niif\NfCiiu;
use App\Traits\ToolsTrait;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

class AportanteController extends Controller
{
    use LeerCsv;
    public function index()
    {
        //
    }

    public function store(AportanteRequest $request)
    {
        try {
            if (!$request->id) {
                $aportante = AsAfiliadoPagador::create($request->all());
            } else {
                $aportante = AsAfiliadoPagador::findOrFail($request->id);
                $aportante->update($request->all());
            }
            $aportante->load('arl','pagador.tercero','afiliado');

        }catch (ValidationException $e)
        {
            return response()->json([
                'message' => 'Los datos enviados son invalidos',
                'errors' => $e->validator
            ], 422);
        }catch (\Exception $e) {
            return response()->json([
                'errors' => $e,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(AsFormtrasmov $formtrasmov, AsAfiliadoPagador $aportante)
    {
        try{
            $aportante->delete();
            return response()->json([
                'message' => 'El aportante se ha removido del trámite.'
            ]);
        } catch (\Exception $e){
            return response()->json([
               'errors' => $e,
               'message' => $e->getMessage()
            ],500);
        }
    }

    public function cargar(Request $request){
        $path_storage = $request->file('aportantes')
            ->storeAs('aportantes', $request->file('aportantes')->getClientOriginalName(),'local');

        ProcesarAportantes::dispatch($path_storage);

        return response()->json()->setStatusCode(201);
    }
}
