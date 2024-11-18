<?php

namespace App\Http\Requests\RedServicios;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EntidadRequest extends FormRequest
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
        $rules =  [
            'gn_tercero_id' => 'required',
            'rs_tipentidade_id' => 'required|exists:rs_tipentidades,id',
            'correo_electronico_sede' => 'required|email',
            'telefono_sede' => 'required',
            'direccion_sede' => 'required',
            'gn_municipiosede_id' => 'required|exists:gn_municipios,id',
            'tipo_locacion' => 'in:Principal,Sede',
            'gerente' => 'required',
            'replegal' => 'required',
            'naturaleza' => 'required|in:Publica,Privada,Mixta',
            'complejidad' => 'required|in:Baja,Media,Alta',
        ];

        if($this->tipentidade_id === 1 ||  $this->tipentidade_id === 6){
            $rules['cod_habilitacion'] =  [
                'required',
                Rule::unique('rs_entidades')->ignore($this->id)
            ];
        }

        return $rules;
    }
}

