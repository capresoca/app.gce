<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Controllers\Controller;
use App\Http\Requests\Presupuesto\EntidadResolucionRequest;
use App\Http\Resources\Presupuesto\EntidadResolucionResource;
use App\Models\Presupuesto\PrEntidadResolucione;
use App\Models\Presupuesto\PrStrgasto;
use App\Models\Presupuesto\PrStringreso;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;

class EntidadResolucionController extends Controller
{

    public function index()
    {
        if(Input::get('per_page')){
            return EntidadResolucionResource::collection(
                PrEntidadResolucione::with('tercero.municipio')
                    ->pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return EntidadResolucionResource::collection(PrEntidadResolucione::with('tercero.municipio')->pimp()->orderBy('updated_at', 'desc')->get());
    }


    public function store(EntidadResolucionRequest $request)
    {
        $entidadResolucion = PrEntidadResolucione::create($request->all());
//        $this->agregar($request, $entidadResolucion);
        return response()->json([
                'message' => 'El cargo de empleados fue creado con exito',
                'data' => PrEntidadResolucione::with('tercero.municipio')->where('id',$entidadResolucion->id)->first()
            ]);
    }


    public function show(PrEntidadResolucione $entidadResolucion)
    {
        return new EntidadResolucionResource($entidadResolucion);
    }


    public function update(EntidadResolucionRequest $request, $id)
    {
        try {
            $entidadResolucion = PrEntidadResolucione::find($id);
            $entidadResolucion->update($request->all());
            $entidadResolucion=$entidadResolucion->with('tercero.municipio')->where('id',$id)->first();
        //        $this->agregar($request, $entidadResolucion);
            return response()->json([
                'message' => 'El cargo de empleados fue editado con exito',
                'data' => $entidadResolucion
            ]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }

    }
    public function findByCodigo($codigo)
    {
        $entidadResolucion = PrEntidadResolucione::with('tercero.municipio')->where('codigo',$codigo)->first();
        if($entidadResolucion){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del cargo de empleados ya se encuentra registrado',
                'data' => $entidadResolucion
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'El cargo de empleados no existe'
        ],204);
    }
    public function findById($id)
    {
        $entidadResolucion = PrEntidadResolucione::with('tercero.municipio')->where('id',$id)->first();
        if($entidadResolucion){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del cargo de empleados ya se encuentra registrado',
                'data' => $entidadResolucion
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'El cargo de empleados no existe'
        ],204);
    }

    public function agregar($request, $entidadResolucion)
    {
        if (!isset($request->id)) {
            $stringreso = new PrStringreso();
            $stringreso->pr_entidad_resolucion_id = $entidadResolucion->id;
            $stringreso->periodo = $request->periodo;
            $stringreso->estado = 'Registrada';
            $stringreso->fecha = Carbon::now()->toDateTimeString();
            $stringreso->save();

            $strgasto = new PrStrgasto();
            $strgasto->pr_entidad_resolucion_id = $entidadResolucion->id;
            $strgasto->periodo = $request->periodo;
            $strgasto->estado = 'Registrada';
            $strgasto->fecha = Carbon::now()->toDateTimeString();
            $strgasto->save();
        }
    }
}
