<?php

namespace App\Http\Requests\Niif;

use App\Traits\EnumsTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NiifRequest extends FormRequest
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
            'codigo' => 'required|string|max:20|unique:nf_niifs,codigo',
            'nombre' => 'required|string|max:150|unique:nf_niifs,nombre',
            'nf_nivcuenta_id' => 'required|exists:nf_nivcuentas,id',
            'nf_clascuenta_id' => 'required|exists:nf_clascuentas,id',
            'nf_padre_id' => 'required|exists:nf_niifs,id',

        ];

        if($this->id){
            $rules['codigo'] = [
                'required',
                'string',
                'max:20',
                Rule::unique('nf_niifs')->ignore($this->id)
            ];

            $rules['nombre'] = [
                'required',
                'string',
                'max:150'
            ];
        }

        return $rules;
    }
}
