<?php

namespace App\Http\Requests\Aseguramiento;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AfpRequest extends FormRequest
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
            'codigo' => 'required|max:8|unique:as_afps,codigo',
            'nombre' => 'required|max:200|unique:as_afps,nombre'
        ];

        if($this->id){
            $rules['codigo'] = [
                'required',
                Rule::unique('as_afps')->ignore($this->id)
            ];


            $rules['nombre'] = [
                'required',
                Rule::unique('as_afps')->ignore($this->id)
            ];
        }

        return $rules;
    }
}
