<?php

namespace App\Http\Requests\Aseguramiento;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EpsRequest extends FormRequest
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
            'cod_habilitacion' => 'required|max:45|unique:as_epss,cod_habilitacion',
            'nombre' => 'required|max:500|unique:as_epss,nombre',
            'nit' => 'required|max:15|unique:as_epss,nit',
            'regimen' => 'required|in:Subsidiado,Contributivo,Especial,PVS',
            'estado' => 'required|in:Activa,Inactiva'
        ];

        $rules['cod_habilitacion'] = [
            'required',
            Rule::unique('as_epss')->ignore($this->id)
        ];


        $rules['nombre'] = [
            'required',
            Rule::unique('as_epss')->ignore($this->id)
        ];

        $rules['nit'] = [
            'required',
            Rule::unique('as_epss')->ignore($this->id)
        ];

        return $rules;
    }
}
