<?php

namespace App\Http\Requests\Aseguramiento;

use Illuminate\Foundation\Http\FormRequest;

class SoltrasladoRequest extends FormRequest
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
            'respuesta' => 'required|in:Negado,Aprobado',
            'as_cautraslado_id' => 'required|exists:as_cautraslados,id',
            'fecha_factible' => 'date|nullable'
        ];
    }
}
