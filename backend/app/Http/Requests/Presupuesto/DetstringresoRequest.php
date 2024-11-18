<?php

namespace App\Http\Requests\Presupuesto;

use Illuminate\Foundation\Http\FormRequest;

class DetstringresoRequest extends FormRequest
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
            'pr_rubro_id' => 'required|exists:pr_conceptos,id',
            'pr_tipo_ingreso_id' => 'required|exists:pr_tipo_ingresos,id',
            'valor_inicial' => 'required'
        ];
    }
}
