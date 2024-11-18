<?php

namespace App\Http\Requests\ContratacionEstatal;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AseguradoraRequest extends FormRequest {
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
			'codigo' => 'required|unique:ce_aseguradoras,codigo',
			'nombre' => 'required',
			'gn_tercero_id' => 'required',
			'direccion' => 'required',
			'telefono' => 'required',
		];

		if ($this->id) {
			$rules['codigo'] = [
				'required',
				Rule::unique('ce_aseguradoras')->ignore($this->id),
			];
		}

		return $rules;
	}
}
