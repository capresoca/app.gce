<?php

namespace App\Http\Requests\Aseguramiento;

use Illuminate\Foundation\Http\FormRequest;

class AportanteRequest extends FormRequest
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
            'as_afiliado_id' => 'required|exists:as_afiliados,id',
            'as_arl_id' => 'required|exists:as_arls,id',
            'as_pagador_id' => 'required|exists:as_pagadores,id'
        ];

        return $rules;
    }
}
