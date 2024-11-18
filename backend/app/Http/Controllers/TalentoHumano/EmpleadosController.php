<?php

namespace App\Http\Controllers\TalentoHumano;

use App\Http\Requests\TalentoHumano\EmpleadosRequest;
use App\Http\Resources\TalentoHumano\EmpleadoResource;
use App\Models\TalentoHumano\ThEmpleado;
use App\Models\Niif\GnTercero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use App\Traits\EnumsTrait;

class EmpleadosController extends Controller
{

    public function index()
    {

        if(Input::get('per_page')){
            return EmpleadoResource::collection(
                ThEmpleado::with('tercero')
                    ->pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return EmpleadoResource::collection(ThEmpleado::with('tercero')->pimp()->orderBy('updated_at', 'desc')->get());
    }


    public function store(EmpleadosRequest $request)
    {
        $empleado = ThEmpleado::create($request->all());
        return response()->json([
                'message' => 'El fondo fue creado con exito',
                'data' => ThEmpleado::with('tercero.municipio_expedicion','tercero.tipo_id','tercero.municipio','grupoempleado','subgrupoempleado','retiro','municipios','cargoinicial','cargoactual','profesionalismo','niif','banco','eps','afps','afpsvoluntaria1','afpsvoluntaria2','afpsvoluntaria3','ccfs')->where('id',$empleado->id)->first()
            ]);
    }


    public function show(ThEmpleado $empleado)
    {
        return new EmpleadoResource($empleado);
    }


    public function update(EmpleadosRequest $request, $id)
    {
        try {
        $empleado = ThEmpleado::find($id);
        $empleado->update($request->all());
        $empleado=$empleado->with('tercero.municipio_expedicion','tercero.tipo_id','tercero.municipio','grupoempleado','subgrupoempleado','retiro','municipios','cargoinicial','cargoactual','profesionalismo','niif','banco','eps','afps','afpsvoluntaria1','afpsvoluntaria2','afpsvoluntaria3','ccfs')->where('id',$id)->first();
        return response()->json([
                'message' => 'El fondo fue editado con exito',
                'data' => $empleado
            ]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }

    }
    public function findByCodigo($codigo)
    {
        $empleado = ThEmpleado::with('tercero.municipio_expedicion','tercero.tipo_id','tercero.municipio','grupoempleado','subgrupoempleado','retiro','municipios','cargoinicial','cargoactual','profesionalismo','niif','banco','eps','afps','afpsvoluntaria1','afpsvoluntaria2','afpsvoluntaria3','ccfs')->where('codigo',$codigo)->first();
        if($empleado){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del fondo ya se encuentra registrado',
                'data' => $empleado
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'El fondo no existe'
        ],204);
    }
    public function findById($id)
    {
        $empleado = ThEmpleado::with('tercero.municipio_expedicion','tercero.tipo_id','tercero.municipio','grupoempleado','subgrupoempleado','retiro','municipios','cargoinicial','cargoactual','profesionalismo','niif','banco','eps','afps','afpsvoluntaria1','afpsvoluntaria2','afpsvoluntaria3','ccfs')->where('id',$id)->first();
        if($empleado){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del fondo ya se encuentra registrado',
                'data' => $empleado
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'El fondo no existe'
        ],204);
    }
    function findTerceroById($id)
    {
        $tercero = GnTercero::where('id',$id)->with('municipio_expedicion','tipo_id','municipio')->first();
        if($tercero){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del tercero ya se encuentra registrado',
                'tercero' => $tercero
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'El tercero no existe'
        ],204);
    }
    function complementos()
    {
        $complemen = [
            'tipoVinculacion' => EnumsTrait::getEnumValues('th_empleados','tipo_vinculacion'),
            'estadosCivil' => EnumsTrait::getEnumValues('th_empleados','estado_civil'),
            'tiposSangre' => EnumsTrait::getEnumValues('th_empleados','tipo_sangre'),
            'jornadaAsignada' => EnumsTrait::getEnumValues('th_empleados','jornada_asignada')
        ];
        return response()->json([
            'data' => $complemen
        ]);
    }
}
