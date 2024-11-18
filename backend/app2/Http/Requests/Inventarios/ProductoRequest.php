<?php

namespace App\Http\Requests\Inventarios;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductoRequest extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		$rules = [
			'codigo' => 'required|max:16|unique:in_productos,codigo',
			'descripcion' => 'required',
			'in_grupo_id' => 'required',
			'in_subgrupo_id' => 'required',
			'in_unidad_medida_id' => 'required',
			'costo_producto' => 'required',
			'codigo_iva' => 'required',
			'fecha_creacion' => 'required',
			'estado' => 'required',
		];

		if ($this->id) {
			$rules['codigo'] = [
				'required',
				Rule::unique('in_productos')->ignore($this->id),
			];
		}

		return $rules;
	}
}
