<?php

namespace App\Http\Requests\ContratacionEstatal;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TipoPolizaRequest extends FormRequest {
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
			'codigo' => 'required|unique:ce_tipo_polizas,codigo',
			'nombre' => 'required',
		];

		if ($this->id) {
			$rules['codigo'] = [
				'required',
				Rule::unique('ce_tipo_polizas')->ignore($this->id),
			];
		}

		return $rules;
	}
}
