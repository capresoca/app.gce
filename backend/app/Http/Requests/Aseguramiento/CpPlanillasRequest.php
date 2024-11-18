<?php

namespace App\Http\Requests\Aseguramiento;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CpPlanillasRequest extends FormRequest
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
            'fecha_pago' => 'required|date',
            'planilla.numero' => 'required'
        ];

        if ($this->novedad === 0) {
            if ($this->ibc === 0 || $this->ibc === null) {
                $rules['ibc'] = 'required|'.Rule::notIn('0');
            }
            if ($this->dias === 0 || $this->dias === null) {
                $rules['dias'] = 'required|'.Rule::notIn('0');
            }
            if ($this->cotizacion === 0 || $this->cotizacion === null) {
                $rules['cotizacion'] = 'required|'.Rule::notIn('0');
            }
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'ibc.*' =>  'Debe agregar un valor válido para el IBC.',
            'dias.*' =>  'Falta agregar el número de días.',
            'cotizacion.*' =>  'Debe agregar un valor válido para el valor del aporte.'
        ];
    }

}
