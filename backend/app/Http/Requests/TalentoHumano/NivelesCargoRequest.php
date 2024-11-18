<?php

namespace App\Http\Requests\TalentoHumano;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NivelesCargoRequest extends FormRequest
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
            'codigo' => 'required|max:16|unique:th_niveles_cargos,codigo',
            'nombre' => 'required'
        ];

        if($this->id){
            $rules['codigo'] = [
                'required',
                Rule::unique('th_niveles_cargos')->ignore($this->id)
            ];
        }

        return $rules;
    }
}
