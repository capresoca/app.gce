<?php

namespace App\Http\Requests\Mipres;

use Illuminate\Foundation\Http\FormRequest;

class SuministroRequest extends FormRequest
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
            'suministro_id' => 'required|exists:mp_direccionamientos',
            'EntregaCompleta' => 'boolean',
            'CausaNoEntrega' => 'nullable|exists:mp_causasnoentregas',
            'ConTecAsociada' => 'numeric'
        ];
    }
}
