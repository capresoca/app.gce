<?php

namespace App\Http\Requests\Tesoreria;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BancoRequest extends FormRequest
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
            'codigo' => 'required|string|max:5|unique:ts_bancos,codigo',
            'nombre' => 'required|string|max:150|unique:ts_bancos,nombre',
            'codigo_ach' => 'required|string|max:10',
            'gn_tercero_id' => 'required|exists:gn_terceros,id'
        ];
        if($this->id){
            $rules['codigo'] = [
                'required',
                Rule::unique('ts_bancos')->ignore($this->id)
            ];


            $rules['nombre'] = [
                'required',
                Rule::unique('ts_bancos')->ignore($this->id)
            ];
        }

        return $rules;
    }
}
