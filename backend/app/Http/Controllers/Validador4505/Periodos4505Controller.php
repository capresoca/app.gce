<?php

namespace App\Http\Controllers\Validador4505;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\EnumsTrait;
use Validator;
use Illuminate\Support\Facades\Input;
use App\Http\Resources\Validador4505\Periodo4505Resource;
use App\Models\Validador4505\RsPeriodos4505;

class Periodos4505Controller extends Controller {

	public function index() {
		try {
			if(Input::get('per_page')){
				return Periodo4505Resource::collection(
					RsPeriodos4505::pimp()
						->orderBy('updated_at','desc')
						->paginate(Input::get('per_page'))
				);
			}
			return Periodo4505Resource::collection(RsPeriodos4505::pimp()->orderBy('updated_at', 'desc')->get());
		} catch (\Exception $e) {
			return response()->json([
				'message' => 'Error en el servidor',
				'errors' => $e->getMessage(),
			]);
		}
	}
	public function store(Request $request) {
		try {
			$validatedData = Validator::make($request->all(), [
				'year' => 'required',
				'periodo' => 'required',
				'fecha_inicio_validacion' => 'required',
				'fecha_fin_validacion' => 'required',
			]);
			if ($validatedData->fails()) {
				return response()->json([
					'state' => 'validador',
					'errors' => $validatedData->errors(),
				]);
			}
			if (!is_null(RsPeriodos4505::where('year',$request->year)->where('periodo',$request->periodo)->first())) {
				return response()->json([
					'state' => 'exist',
					'message' => 'Ya existe un periodo para el año: '.$request->year.' y periodo '.$request->periodo.' seleccionado',
				]);
			}
			$periodo = RsPeriodos4505::create($request->all());
			return response()->json([
				'state' => 'save',
				'data' => RsPeriodos4505::where('id',$periodo->id)->first()
			]);
		} catch (\Exception $e) {
			return response()->json([
				"message" => "Error en el servidor. ".$e->getMessage(),
				"error" => $e->getMessage()
			]);
		}
	}
	public function update(Request $request, $id)
	{
		try {
			$validatedData = Validator::make($request->all(), [
				'year' => 'required',
				'periodo' => 'required',
				'fecha_inicio_validacion' => 'required',
				'fecha_fin_validacion' => 'required',
			]);
			if ($validatedData->fails()) {
				return response()->json([
					'state' => 'validador',
					'errors' => $validatedData->errors(),
				]);
			}
			$periodo = RsPeriodos4505::find($id);
			if ($periodo->year != $request->year) {
				if (!is_null(RsPeriodos4505::where('year',$request->year)->where('periodo',$request->periodo)->first())) {
					return response()->json([
						'state' => 'exist',
						'message' => 'No se puede Actualizar. Ya existe un periodo para el año: '.$request->year.' y periodo '.$request->periodo.' seleccionado',
						'data' => $periodo,
					]);
				}
			}
			if ($periodo->periodo != $request->periodo) {
				if (!is_null(RsPeriodos4505::where('year',$request->year)->where('periodo',$request->periodo)->first())) {
					return response()->json([
						'state' => 'exist',
						'message' => 'No se puede Actualizar. Ya existe un periodo para el año: '.$request->year.' y periodo '.$request->periodo.' seleccionado',
						'data' => $periodo,
					]);
				}
			}
			$periodo->update($request->all());
			return response()->json([
				'state' => 'save',
				'message' => 'El periodo fue editado con exito',
				'data' => RsPeriodos4505::where('id',$periodo->id)->first()
			]);
		} catch (\Exception $e) {
			return response()->json([
				"message" => "Error en el servidor. ".$e->getMessage(),
				"error" => $e->getMessage()
			]);
		}

	}
	public function complementos()
	{
		try {
			return response()->json([
				'periodos' => EnumsTrait::getEnumValues('rs_periodos4505','periodo'),
				'years' => RsPeriodos4505::select('year')->groupBy('year')->orderBy('year','asc')->get()
			]);
		} catch (\Exception $e) {
			return response()->json([
				'state' => 'exception',
				'message' => 'Error en el servidor al traer complementos ' . $e->getMessage(),
				'errors' => $e->getMessage(),
			], 500);
		}
	}
    public function findById($id)
    {
        $periodo = RsPeriodos4505::where('id',$id)->first();
        if($periodo){
             return response()->json([
                'exists' => true,
                'message'=> 'Periodo existe',
                'data' => $periodo
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'El identificador del periodo no existe'
        ],204);
    }
}
?>