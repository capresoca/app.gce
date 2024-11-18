<?php

namespace App\Http\Requests\Aseguramiento;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CcfRequest extends FormRequest
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
            'cod_habilitacion' => 'required|max:6|unique:as_ccfs,cod_habilitacion',
            'nombre' => 'required|max:200|unique:as_ccfs,nombre'
        ];


        $rules['cod_habilitacion'] = [
            'required',
            Rule::unique('as_ccfs')->ignore($this->id)
        ];


        $rules['nombre'] = [
            'required',
            Rule::unique('as_ccfs')->ignore($this->id)
        ];

        return $rules;
    }
}
