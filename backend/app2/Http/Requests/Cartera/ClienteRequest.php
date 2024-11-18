<?php

namespace App\Http\Requests\Cartera;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClienteRequest extends FormRequest
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
            'codigo' => 'required|max:16|unique:cr_clientes,codigo',
            'nombre' => 'required',
            'gn_tercero_id' => 'required',
            'cr_vendedor_id' => 'required',
            'nf_niif_id' => 'required',
        ];

        if($this->id){
            $rules['codigo'] = [
                'required',
                Rule::unique('cr_clientes')->ignore($this->id)
            ];
        }

        return $rules;
    }
}
