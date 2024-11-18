<?php

namespace App\Http\Requests\RedServicios;

use Illuminate\Foundation\Http\FormRequest;

class AfiliadoservicioResquest extends FormRequest
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
            'tipo_proceso' => 'required|in:Asignar servicios,Trasladar servicios',
            'afiliados.*.as_afiliado_id' => 'required|numeric|exists:as_afiliados,id',
            'servicios_habilitados.*.rs_servhabilitado_id' => 'required|numeric|exists:rs_servhabilitados,id'
        ];
        if ($this->tipo_proceso == 'Trasladar servicios') {
            $rules['rs_servhabilitado_anterior'] = 'required|numeric|exists:rs_servhabilitados,id';
        }
        return $rules;
    }
}
