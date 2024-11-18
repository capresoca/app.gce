<?php

namespace App\Http\Requests\Cartera;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VendedorRequest extends FormRequest
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
            'codigo' => 'required|max:16|unique:cr_vendedores,codigo',
            'gn_tercero_id' => 'required',
            'nombre' => 'required'
        ];

        if($this->id){
            $rules['codigo'] = [
                'required',
                Rule::unique('cr_vendedores')->ignore($this->id)
            ];
        }

        return $rules;
    }
}
