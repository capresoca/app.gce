<?php

namespace App\Http\Requests\ContratacionEstatal;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BienservicioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'ce_clasbienservicio_id' => 'required|exists:ce_clasbienservicios,id',
            'nombre' => 'required|unique:ce_bienservicios,nombre'
        ];

        $rules['nombre'] = [
            'required',
            Rule::unique('ce_bienservicios')->ignore($this->id)
        ];
        return $rules;
    }
}
