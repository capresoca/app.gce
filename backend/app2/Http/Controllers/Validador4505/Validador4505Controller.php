<?php

namespace App\Http\Controllers\Validador4505;

use App\Http\Controllers\Controller;
use App\Http\Resources\CuentasMedicas\RadicadoResource;
use App\Models\CuentasMedicas\CmConcurrencia;
use App\Models\Validador4505\RsValidador4505;
use App\Models\Validador4505\Unificacion;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use App\Traits\EnumsTrait;
use Validator;
use App\Models\Validador4505\RsPeriodos4505;

class Validador4505Controller extends Controller {

	public function index() {

	    if(Input::get('per_page')){
            $validadciones = RsValidador4505:: where('estado_radicacion', 'Validado')->with(['RsEntidad.tercero', 'RsEntidad.tipo'])
                ->pimp()->orderBy('updated_at')->paginate(Input::get('per_page'));
            return  JsonResource::collection($validadciones);
        }
        return  JsonResource::collection(RsValidador4505::with(['RsEntidad.tercero', 'RsEntidad.tipo'])->pimp()
            ->orderBy('updated_at')->get());

	}
	public function store(Request $request) {
		try {
			$validatedData = Validator::make($request->all(), [
				'id' => 'required',
			]);
			if ($validatedData->fails()) {
				return response()->json([
					'estado' => 'validador',
					'errors' => $validatedData->errors(),
				]);
			}
			$archivo4505Radicado = RsValidador4505::where('id', $request->id)->first();
			$archivo4505Radicado->estado_radicacion = 'Confirmado';
			$archivo4505Radicado->save();
			return response()->json([
				'estado' => 'ok',
			]);
		} catch (\Exception $e) {
			return response()->json([
				"message" => "Error en el servidor",
				"error" => $e->getMessage(),
				"trace" => $e->getTrace(),
			]);
		}
	}
	public function getArchivo4505($id4505) {
		try {
			$validadorRadicado = RsValidador4505::where('id', $id4505)->first();
			if (is_null($validadorRadicado)) {
				return response()->json([
					'state' => 'error',
					'error' => 'El ID del radicado no es valido',
				], 513);
			}
			if (!Storage::disk('s3')->exists('Validador4505\\' . $validadorRadicado->cod_radicacion . '\\' . $validadorRadicado->cod_radicacion_ct . '.TXT')) {
				return response()->json([
					'state' => 'error',
					'error' => 'El archivo de errores no se encontró dentro de la ruta especificada',
				], 513);
			}
			return response(Storage::disk('s3')->get('Validador4505\\' . $validadorRadicado->cod_radicacion . '\\' . $validadorRadicado->cod_radicacion_ct . '.TXT'));
		} catch (\Exception $e) {
			return response()->json([
				'message' => 'Error en el servidor',
				'errors' => $e->getMessage(),
			]);
		}
	}
	public function unificador4505(Request $request) {
		try {
			$validatedData = Validator::make($request->all(), [
				'year' => 'required',
				'periodo' => 'required',
			]);
			if ($validatedData->fails()) {
				return response()->json([
					'state' => 'validador',
					'errors' => $validatedData->errors(),
					'message' => 'El año y el periodo son requeridos',
				], 513);
			}
			$periodo = RsPeriodos4505::where('year',$request->year)->where('periodo',$request->periodo)->first();
			if (is_null($periodo)) {
				return response()->json([
					'state' => 'warning',
					'message' => 'No existen periodos activos',
				], 514);
			}
			$datosValidador=RsValidador4505::where('rs_periodos4505_id',$periodo->id)->get();
			if (count($datosValidador) == 0) {
				return response()->json([
					'state' => 'warning',
					'message' => 'No existen archivos 4505 con el periodo seleccionado',
				], 514);
			}
			$datos = [];
			foreach ($datosValidador as $value) {
				if ($value->estado_radicacion == 'Validado' || $value->estado_radicacion == 'Confirmado') {
					$datos[] = $value;
				}
			}
			$datosValidador = $datos;
			if (count($datosValidador) == 0) {
				return response()->json([
					'state' => 'warning',
					'message' => 'No existen Archivos 4505 Validados o Confirmados con el periodo asignado',
				], 514);
			}
			$archivos = [];
			foreach ($datosValidador as $datos4505) {
				if (Storage::disk('s3')->exists('Validador4505\\' . $datos4505->cod_radicacion . '\\' . $datos4505->cod_radicacion_ct . '.TXT')) {
					$archivos[] = Storage::disk('s3')->get('Validador4505\\' . $datos4505->cod_radicacion . '\\' . $datos4505->cod_radicacion_ct . '.TXT');
				} elseif (Storage::disk('s3')->exists('Validador4505\\' . $datos4505->cod_radicacion . '\\' . $datos4505->cod_radicacion_ct . '.txt')) {
					$archivos[] = Storage::disk('s3')->get('Validador4505\\' . $datos4505->cod_radicacion . '\\' . $datos4505->cod_radicacion_ct . '.txt');
				}
			}
			$unificacion = new Unificacion($archivos);
			$arrayArchivo = $unificacion->unificarArchivo();
			if (!is_array($arrayArchivo)) {
				if (!arrayArchivo) {
					return response()->json([
						'state' => 'error',
						'message' => 'No se pueden unificar los archivos, Archivos mal conformados.',
					], 515);
				}
			}
			return response(implode('', $arrayArchivo));
		} catch (\Exception $e) {
			return response()->json([
				'state' => 'exception',
				'message' => 'Error en el servidor ' . $e->getMessage(),
				'errors' => $e->getMessage(),
			], 500);
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
}
?>