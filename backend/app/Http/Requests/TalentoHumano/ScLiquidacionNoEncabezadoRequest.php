<?php

namespace App\Http\Requests\TalentoHumano;

use Illuminate\Foundation\Http\FormRequest;

class ScLiquidacionNoEncabezadoRequest extends FormRequest
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
            'descripcion'           => 'required',
            'ano'                   => 'required',
            'mes'                   => 'required',
            'periodo'               => 'required',
            'centro_costo'          => 'required',
            'area'                  => 'required',
            'dependencia'           => 'required',
            'fecha'                 => 'required|date',
            'estado'                => 'nullable',
            'empresa'               => 'required',
            'liquidacion_operador'  => 'required',
            'sw_prima'              => 'required',
            'sw_interes_cesantia'   => 'required',
            'consecutivo_saldo'     => 'required',
            'valor_nomina'          => 'required',
            'tipo_nomina'           => 'required'
        ];

        return $rules;
    }
}
