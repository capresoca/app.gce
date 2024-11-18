<?php

namespace App\Http\Requests\AtencionUsuario;

use Illuminate\Foundation\Http\FormRequest;

class RespuestaPqrsdRequest extends FormRequest
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
            'respuesta' => 'required',
            'tipo' => 'required|in:Positiva,Negativa,Otra',
            'fecha_respuesta' => 'required|date',
            'estado' => 'required|in:Registrada,Confirmada'
        ];
    }
}
