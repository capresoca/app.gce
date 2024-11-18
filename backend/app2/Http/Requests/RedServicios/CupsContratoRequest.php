<?php

namespace App\Http\Requests\RedServicios;

use App\Traits\EnumsTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CupsContratoRequest extends FormRequest
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
        $tipos_manual = EnumsTrait::getEnumValues('rs_cupscontratados','tipo_manual');
        $rules = [
            'cups'              => 'required',
            'tipo_manual'       => [
                                        'required',
                                        Rule::in($tipos_manual)
                                    ]

        ];


        foreach ($this->cups as $key => $cup){
            $rules['cups.'.$key] = 'required|exists:rs_cupsentidades,id';
        }

        if($this->tipo_manual != 'Institucional'){
            $rules['porcentaje'] = 'required';
        }

        if($this->tipo_manual === 'SOAT'){
            $rules['rs_salminimo_id'] = 'required|exists:rs_salminimos,id';
        }

        return $rules;
    }
}
