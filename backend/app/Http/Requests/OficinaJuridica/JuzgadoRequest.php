<?php

namespace App\Http\Requests\OficinaJuridica;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JuzgadoRequest extends FormRequest
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
            'nombre' => 'required|unique:oj_juzgados,nombre',
            'gn_municipio_id' => 'required|exists:gn_municipios,id'
        ];

        $rules['nombre'] = [
            'required',
            Rule::unique('oj_juzgados')->ignore($this->id)
        ];

        return $rules;
    }
}
