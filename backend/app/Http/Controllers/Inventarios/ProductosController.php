<?php

namespace App\Http\Controllers\Inventarios;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventarios\ProductoRequest;
use App\Http\Resources\Inventarios\ProductoResource;
use App\Models\Inventarios\InProducto;
use Illuminate\Support\Facades\Input;

class ProductosController extends Controller {

	public function index() {

		if (Input::get('per_page')) {
			return ProductoResource::collection(
				InProducto::with('grupo', 'subgrupo', 'unidadmedida')
					->pimp()
					->orderBy('updated_at', 'desc')
					->paginate(Input::get('per_page'))
			);
		}
		return ProductoResource::collection(InProducto::with('grupo', 'subgrupo', 'unidadmedida')->pimp()->orderBy('updated_at', 'desc')->get());
	}

	public function store(ProductoRequest $request) {
		$producto = InProducto::create($request->all());
		return response()->json([
			'message' => 'El producto fue creado con exito',
			'data' => InProducto::where('id', $producto->id)->with('grupo', 'subgrupo', 'unidadmedida')->first(),
		]);
	}

	public function show(InProducto $producto) {
		return new ProductoResource($producto);
	}

	public function update(ProductoRequest $request, $id) {
		try {
			$producto = InProducto::find($id);
			$producto->update($request->all());
			$producto = $producto->where('id', $id)->with('grupo', 'subgrupo', 'unidadmedida')->first();
			return response()->json([
				'message' => 'El producto fue editado con exito',
				'data' => $producto,
			]);
		} catch (Exception $e) {
			return response()->json($e->getMessage());
		}

	}
	public function findByCodigo($codigo) {
		$producto = InProducto::where('codigo', $codigo)->with('grupo', 'subgrupo', 'unidadmedida')->first();
		if ($producto) {
			return response()->json([
				'exists' => true,
				'message' => 'El codigo del producto ya se encuentra registrado',
				'data' => $producto,
			], 200);
		}
		return response()->json([
			'exists' => false,
			'message' => 'El producto no existe',
		], 204);
	}
	public function findById($id) {
		$producto = InProducto::where('id', $id)->with('grupo', 'subgrupo', 'unidadmedida')->first();
		if ($producto) {
			return response()->json([
				'exists' => true,
				'message' => 'El codigo del producto ya se encuentra registrado',
				'data' => $producto,
			], 200);
		}
		return response()->json([
			'exists' => false,
			'message' => 'El producto no existe',
		], 204);
	}
}
