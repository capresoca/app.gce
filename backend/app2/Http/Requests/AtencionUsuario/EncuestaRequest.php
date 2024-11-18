<?php

namespace App\Http\Requests\AtencionUsuario;

use Illuminate\Foundation\Http\FormRequest;

class EncuestaRequest extends FormRequest
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
            'fecha' => 'required',
            'as_afiliado_id' => 'required|exists:as_afiliados,id',
            'gn_municipio_id' => 'required|exists:gn_municipios,id',
            'lugar' => 'required|max:500',
            'respuestas' => 'required'
        ];
    }
}
