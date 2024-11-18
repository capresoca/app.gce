<?php

namespace App\Http\Requests\Presupuesto;

use Illuminate\Foundation\Http\FormRequest;

class StringresoRequest extends FormRequest
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
            'pr_entidad_resolucion_id' => 'required|exists:pr_entidad_resoluciones,id',
            'periodo' => 'required',
            'estado' => 'required|in:Registrada,Confirmada,Anulada'
        ];

        return $rules;
    }
}
