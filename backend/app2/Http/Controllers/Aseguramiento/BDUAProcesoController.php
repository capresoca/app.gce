<?php

namespace App\Http\Controllers\Aseguramiento;

use App\Capresoca\Aseguramiento\GeneradorArchivosBDUA;
use App\Http\Requests\Aseguramiento\BDUAProcesoRequest;
use App\Http\Resources\Aseguramiento\BDUAProcesosResource;
use App\Jobs\GeneraBDUAArchivo;
use App\Models\Aseguramiento\AsBduaarchivo;
use App\Models\Aseguramiento\AsBduaproceso;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class BDUAProcesoController extends Controller
{

    public function index()
    {
        if (Input::get('per_page')) {
            return BDUAProcesosResource::collection(
                AsBduaproceso::with('archivos.tipo', 'archivos.respuestas')
                    ->pimp()
                    ->orderBy('updated_at', 'desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return BDUAProcesosResource::collection(AsBduaproceso::with('archivos.tipo')->pimp()->orderBy('updated_at', 'desc')->get());

    }

    public function store(BDUAProcesoRequest $request)
    {
        DB::beginTransaction();
        $proceso = AsBduaproceso::firstOrCreate([
            'fecha' => $request->fecha,
            'tipo' => $request->tipo
        ]);

//            new GeneradorArchivosBDUA($proceso);
        GeneraBDUAArchivo::dispatch($proceso)->onQueue('archivos');

        $proceso->load('archivos.tipo');

        DB::commit();
        return new BDUAProcesosResource($proceso);
    }

    public function show(AsBduaproceso $bduaproceso)
    {
        $response = new BDUAProcesosResource($bduaproceso->load('archivos.tipo', 'archivos.respuestas'));
        return $response;
    }
}
