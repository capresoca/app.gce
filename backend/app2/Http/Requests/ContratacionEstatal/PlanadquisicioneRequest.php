<?php

namespace App\Http\Requests\ContratacionEstatal;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlanadquisicioneRequest extends FormRequest
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
            'anio' => 'required|year|max:4|unique:ce_planadquisiciones,anio',
            'estado' => 'required|in:Registrado,Activo,Inactivo,Radicado'
        ];

        $rules['anio'] = [
            'required',
            Rule::unique('ce_planadquisiciones')->ignore($this->id)
        ];

        return $rules;
    }
}
