<?php

namespace App\Http\Requests\Aseguramiento;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BeneficiarioRequest extends FormRequest
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
            'as_parentesco_id' => 'required|exists:as_parentescos,id',
            'rs_entidade_id' => 'required|exists:rs_entidades,id'
        ];

        if (!isset($this->as_formafiliacion_id)) {
            $rules['as_formtrasmov_id'] = 'required|exists:as_formtrasmovs,id';
            if(isset($this->as_formtrasmov)){
                $rules['as_beneficiario_id'] = [
                    'required',
                    'exists:as_afiliados,id',
                    Rule::unique('as_nucfamiliares','as_beneficiario_id')->where(function($query){
                        return $query->where('as_formtrasmov',$this->as_formtrasmov_id);
                    })->ignore($this->id)
                ];
            }
        }

        if(!isset($this->as_formtrasmov_id)){
            $rules['as_formafiliacion_id'] = 'required|exists:as_formafiliaciones,id';
            if(isset($this->as_formafiliacion_id)){
                $rules['as_beneficiario_id'] = [
                    'required',
                    'exists:as_afiliados,id',
                    Rule::unique('as_nucfamiliares','as_beneficiario_id')->where(function($query) {
                        return $query->where('as_formafiliacion_id',$this->as_formafiliacion_id);
                    })->ignore($this->id)
                ];
            }
        }

        return $rules;
    }
}
