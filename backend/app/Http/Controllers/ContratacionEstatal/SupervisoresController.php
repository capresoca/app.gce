<?php

namespace App\Http\Controllers\ContratacionEstatal;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContratacionEstatal\SupervisoresRequest;
use App\Http\Resources\ContratacionEstatal\SupervisoresResource;
use App\Models\ContratacionEstatal\CeSupervisore;
use Illuminate\Support\Facades\Input;

class SupervisoresController extends Controller {

	public function index() {

		if (Input::get('per_page')) {
			return SupervisoresResource::collection(
				CeSupervisore::with('tercero')
					->pimp()
					->orderBy('updated_at', 'desc')
					->paginate(Input::get('per_page'))
			);
		}
		return SupervisoresResource::collection(CeSupervisore::with('tercero')->pimp()->orderBy('updated_at', 'desc')->get());
	}

	public function store(SupervisoresRequest $request) {
		$supervisor = CeSupervisore::create($request->all());
		return response()->json([
			'message' => 'El supervisor fue creado con exito',
			'data' => CeSupervisore::where('id', $supervisor->id)->with('tercero')->first(),
		]);
	}

	public function show(CeSupervisore $supervisor) {
		return new SupervisoresResource($supervisor->load('tercero'));
	}

	public function update(SupervisoresRequest $request, $id) {
		try {
			$supervisor = CeSupervisore::find($id);
			$supervisor->update($request->all());
			$supervisor = $supervisor->where('id', $id)->with('tercero')->first();
			return response()->json([
				'message' => 'El supervisor fue editado con exito',
				'data' => $supervisor,
			]);
		} catch (Exception $e) {
			return response()->json($e->getMessage());
		}

	}

	public function findByCodigo($codigo) {
		$supervisor = CeSupervisore::where('codigo', $codigo)->with('tercero')->first();
		if ($supervisor) {
			return response()->json([
				'exists' => true,
				'message' => 'El codigo del supervisor ya se encuentra registrado',
				'supervisor' => $supervisor,
			], 200);
		}
		return response()->json([
			'exists' => false,
			'message' => 'El supervisor no existe',
		], 204);
	}
	public function findById($id) {
		$supervisor = CeSupervisore::where('id', $id)->with('tercero')->first();
		if ($supervisor) {
			return response()->json([
				'exists' => true,
				'message' => 'El codigo del supervisor ya se encuentra registrado',
				'data' => $supervisor,
			], 200);
		}
		return response()->json([
			'exists' => false,
			'message' => 'El supervisor no existe',
		], 204);
	}
}
