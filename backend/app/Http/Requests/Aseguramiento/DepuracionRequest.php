<?php

namespace App\Http\Requests\Aseguramiento;

use Illuminate\Foundation\Http\FormRequest;

class DepuracionRequest extends FormRequest
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
            'as_afiliado_id' => 'required|exists:as_afiliados,id',
            'as_tipnovedade_id' => 'required|exists:as_tipnovedades,id',
//            'as_fecha_ini_novedad' => 'date|after:'. today()->subDays(375)->toDateString().'|before:'.today()->addDays(40)->toDateString()
        ];

        return $rules;
    }
}
