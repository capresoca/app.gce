<?php

namespace App\Http\Requests\Autorizaciones;

use App\Models\Autorizaciones\AuMedicosSolicitante;
use Illuminate\Foundation\Http\FormRequest;

class MedicoRequest extends FormRequest
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
            'codigo' => 'required|unique:au_medicos_solicitantes,codigo',
            'descripcion' => 'required',
            'au_especialidad_id' => 'required|exists:au_especialidads,id'
        ];

        $exist = AuMedicosSolicitante::where([
            'codigo'                => $this->codigo,
            'au_especialidad_id'    => $this->au_especialidad_id
        ])->where('id','<>',$this->id)->first();

        if($exist){
            $rules['medico_existente'] = 'required';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'medico_existente.required' => 'El medico ya existe en la base de datos'
        ];
    }
}
