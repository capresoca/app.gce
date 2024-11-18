<?php

namespace App\Http\Requests\Presupuesto;

use App\Traits\EnumsTrait;
use Illuminate\Foundation\Http\FormRequest;

class PrTrasladoRequest extends FormRequest
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
            'periodo' => 'required',
            'fecha' => 'required|date',
            'documento' => 'required',
            'observaciones' => 'required',
            'estado' => 'required|in:' . implode(',', EnumsTrait::getEnumValues('pr_traslados','estado')),
            'tipo' => 'required|in:' . implode(',', EnumsTrait::getEnumValues('pr_traslados','tipo')),
            'detalles' => 'required'
        ];

        if ($this['tipo'] === 'Gasto') {
            $rules['pr_strgasto_id'] = 'required|exists:pr_strgastos,id';
        } elseif ($this['tipo'] === 'Ingreso') {
            $rules['pr_stringreso_id'] = 'required|exists:pr_stringresos,id';
        }

        if ($this['estado'] === 'Anulado') {
            $rules['concepto_anulacion'] = 'required';
        }

        return $rules;
    }
}
