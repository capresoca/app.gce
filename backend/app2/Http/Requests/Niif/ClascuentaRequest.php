<?php

namespace App\Http\Requests\Niif;

use App\Traits\EnumsTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClascuentaRequest extends FormRequest
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
        $tipos = EnumsTrait::getEnumValues('nf_clascuentas', 'tipo');
        $naturalezas = EnumsTrait::getEnumValues('nf_clascuentas', 'naturaleza');


        $rules = [
            'codigo' => 'required|string|max:5|unique:nf_clascuentas,codigo',
            'nombre' => 'required|string|max:150|unique:nf_clascuentas,nombre',
            'tipo' => [
                'required',
                Rule::in($tipos)
            ],
            'naturaleza' => [
                'required',
                Rule::in($naturalezas)
            ]
        ];

        if($this->id){
            $rules['codigo'] = [
                'required',
                'string',
                'max:5',
                Rule::unique('nf_clascuentas')->ignore($this->id)
            ];

            $rules['nombre'] = [
                'required',
                'string',
                'max:150',
                Rule::unique('nf_clascuentas')->ignore($this->id)
            ];
        }

        return $rules;

    }
}
