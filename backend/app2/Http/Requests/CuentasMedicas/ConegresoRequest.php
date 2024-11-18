<?php

namespace App\Http\Requests\CuentasMedicas;

use App\Traits\EnumsTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConegresoRequest extends FormRequest
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
        $estados_salida = EnumsTrait::getEnumValues('cm_conegresos','estado_salida');
        $rules = [
            'cm_concurrencia_id'   => 'required|exists:cm_concurrencias,id|unique:cm_conegresos',
            'fecha_egreso'          => 'required|date',
            'estado_salida'         => ['required', Rule::in($estados_salida)],
            'rs_entidad_destino_id' => 'nullable|numeric|exists:rs_entidades,id',
            'dx_egreso'             => 'required|numeric|exists:rs_cie10s,id',
            'dx_relacionado'        => 'nullable|numeric|exists:rs_cie10s,id'
        ];

        if($this->estado_salida === 'Remitido'){
            $rules['rs_entidad_destino_id'] = 'nullable|exists:rs_entidades,id';
        }

        return $rules;
    }
}
