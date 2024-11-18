<?php

namespace App\Http\Requests\Niif;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NivcuentaRequest extends FormRequest
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
            'nombre' => 'required|string|max:150'
        ];

        if($this->id){
            $rules['codigo'] = [
                'required',
                'max:5',
                Rule::unique('nf_nivcuentas')->ignore($this->id)
            ];

            $rules['nombre'] = [
                'required',
                'string',
                'max:150',
                Rule::unique('nf_nivcuentas')->ignore($this->id)
            ];
        }
        return $rules;
    }
}
