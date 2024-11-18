<?php

namespace App\Http\Requests\Tesoreria;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConceptoReciboRequest extends FormRequest
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
            'codigo' => 'required|unique:ts_concepto_recibos,codigo',
            'nombre' => 'required',
            'nf_niif_id' => 'required',
        ];
        if($this->id){
            $rules['codigo'] = [
                'required',
                Rule::unique('ts_concepto_recibos')->ignore($this->id)
            ];
        }

        return $rules;
    }
}