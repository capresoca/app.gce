<?php

namespace App\Http\Requests\ContratacionEstatal;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NatjuridicaRequest extends FormRequest
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
            'codigo' => 'required|unique:ce_natjuridicas,codigo',
            'nombre' => 'required|unique:ce_natjuridicas,nombre',
            'estado' => 'required|in:Activo,Inactivo'
        ];

        $rules['codigo'] = [
            'required',
            Rule::unique('ce_natjuridicas')->ignore($this->id)
        ];

        $rules['nombre'] = [
            'required',
            Rule::unique('ce_natjuridicas')->ignore($this->id)
        ];

        return $rules;
    }
}
