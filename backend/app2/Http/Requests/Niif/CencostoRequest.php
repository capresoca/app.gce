<?php

namespace App\Http\Requests\Niif;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CencostoRequest extends FormRequest
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
            'codigo' => 'required|string|max:5|unique:nf_cencostos,codigo',
            'nombre' => 'required|string|max:150'
        ];

        if($this->id){
            $rules['codigo'] = [
                'required',
                Rule::unique('nf_cencostos')->ignore($this->id)
            ];

            $rules['nombre'] = [
                'required',
                Rule::unique('nf_cencostos')->ignore($this->id)
            ];
        }

        return $rules;
    }
}
