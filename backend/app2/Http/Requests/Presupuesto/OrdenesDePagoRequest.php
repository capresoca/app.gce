<?php

namespace App\Http\Requests\Presupuesto;

use App\Traits\EnumsTrait;
use Illuminate\Foundation\Http\FormRequest;

class OrdenesDePagoRequest extends FormRequest
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
            'fecha' => 'required|date',
            'valor_total_orden' => 'required',
            'pr_entidad_resolucion_id' => 'required|exists:pr_entidad_resoluciones,id',
            'gn_tercero_id' => 'required|exists:gn_terceros,id',
            'estado' => 'required|in:' . implode(',', EnumsTrait::getEnumValues('pr_ordenes_de_pagos','estado')),
            'detalles' => 'required'
        ];

        return $rules;
    }
}
