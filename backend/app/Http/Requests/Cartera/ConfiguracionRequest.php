<?php

namespace App\Http\Requests\Cartera;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConfiguracionRequest extends FormRequest
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
            'nf_niif_cxc_id' => 'required',
            'nf_niif_glosas_id' => 'required',
            'nf_tipcomdiario_debito_id' => 'required',
            'nf_tipcomdiario_credito_id' => 'required',
            'nf_tipcomdiario_traslados_id' => 'required',
            'nf_tipcomdiario_cxc_id' => 'required',
            'nf_tipcomdiario_rglosas_id' => 'required',
            'nf_tipcomdiario_cglosas_id' => 'required',
            'nf_tipcomdiario_pcartera_id' => 'required',
            'nf_tipcomdiario_responsabilidades_id' => 'required',
            'nf_tipcomdiario_cdr_id' => 'required',
            'nf_tipcomdiario_nmp_id' => 'required',
            'nf_tipcomdiario_rap_id' => 'required',
            'edad_inicial' => 'required',
            'edad_rango1' => 'required',
            'edad_rango2' => 'required',
            'edad_rango3' => 'required',
            'edad_rango4' => 'required'
        ];

        return $rules;
    }
}