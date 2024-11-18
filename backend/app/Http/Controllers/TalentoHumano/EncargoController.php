<?php

namespace App\Http\Controllers\TalentoHumano;

use App\Http\Requests\TalentoHumano\EncargoRequest;
use App\Http\Resources\TalentoHumano\EncargoResource;
use App\Models\Presupuesto\PrEntidadResolucione;
use App\Models\TalentoHumano\ThEncargo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\ValidationException;

class EncargoController extends Controller
{

    public function index()
    {
        if (Input::get('per_page')) {
            return EncargoResource::collection(
                ThEncargo::with('usuario')->pimp()->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }

        return EncargoResource::collection(ThEncargo::with('usuario')->pimp()->orderBy('updated_at','desc')->get());
    }

    public function store(EncargoRequest $request)
    {
        try {
            $encargo = new ThEncargo ();
            $encargo->fill($request->except('pr_entidad_resolucion_id'));
            $entidad_resolucion = PrEntidadResolucione::where('periodo', Carbon::now()->year)->first();
            $encargo->pr_entidad_resolucion_id = $entidad_resolucion->id;
            $encargo->save();
            $encargo->load('usuario');

            return response()->json([
                'data' => new EncargoResource($encargo),
                'msg' => 'Se ha creado correctamente el registro encargo.'
            ], 201);

        } catch (ValidationException $validationException) {
            return response()->json([
                'error' => $validationException,
                'msg' => $validationException->getMessage()
            ], 404);
        }catch (\Exception $e) {
            return response()->json([
                'error' => $e,
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    public function show(ThEncargo $encargo)
    {
        return new Resource($encargo->load('usuario'));
    }

    public function update(EncargoRequest $request, $id)
    {
        try {
            $encargo = ThEncargo::find($id);
            $encargo->fill($request->except('pr_entidad_resolucion_id'));
            $entidad_resolucion = PrEntidadResolucione::where('periodo', Carbon::now()->year)->first();
            $encargo->pr_entidad_resolucion_id = $entidad_resolucion->id;
            $encargo->save();
            $encargo->load('usuario');

            return response()->json([
                'data' => new EncargoResource($encargo),
                'msg' => 'Se ha actualizado correctamente el registro encargo.'
            ], 202);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function destroy($id){}
}
