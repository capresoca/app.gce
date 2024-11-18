<?php

namespace App\Http\Requests\Mipres;

use Illuminate\Foundation\Http\FormRequest;

class DireccionamientoRequest extends FormRequest
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
        return [
            //'mp_prescripcion_id' => 'exists:mp_prescripciones,id',
            //'mp_tutela_id' => 'exists:mp_tutelas,id',
            'NoPrescripcion' => 'required',
            'TipoTec' => 'required|in:M,S,N,P,',
            'ConTec' => 'required',
            'TipoIDPaciente' => 'required',
            'NoIDPaciente' => 'required',
            //'as_afiliado_id' => 'required|exists:as_afiliados,id',
            'NoEntrega' => 'required|numeric',
            'TipoIDProv' => 'required',
            'NoIDProv' => 'required',
            'rs_entidade_id' => 'required',
            'CodMunEnt' => 'required',
            'gn_munentidad_id' => 'required',
            'FecMaxEnt' => 'required',
            'CantTotAEntregar' => 'required',
            //'DirPaciente' => 'required',
            'CodSerTecAEntregar' => 'required|max:20',
        ];
    }

}
