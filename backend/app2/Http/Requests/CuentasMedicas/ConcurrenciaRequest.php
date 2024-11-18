<?php

namespace App\Http\Requests\CuentasMedicas;

use App\Traits\EnumsTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConcurrenciaRequest extends FormRequest
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
        $opciones = EnumsTrait::getEnumValues('cm_concurrencias', 'via_ingreso');
        $rules = [
            'as_afiliado_id' => 'required |exists:as_afiliados,id',
            // 'consecutivo_ips' => '',
            'fecha' => 'required|date',
            'rs_entidad_id' => 'required|numeric|exists:rs_entidades,id',
            'rs_servicio_id' => 'required|numeric|exists:rs_servicios,id',
            'rs_especialidad_id' => 'required|numeric|exists:rs_servicios,id',
            'au_referencia_id' => 'nullable|numeric|exists:au_referencias,id',
            'via_ingreso' => ['required', Rule::in($opciones)],
            'rs_cie10_ingreso' => 'required|exists:rs_cie10s,id',
            'rs_cie10_relacionado' => 'nullable|numeric|exists:rs_cie10s,id'
        ];

        if ($this->via_ingreso == 'Remitido') {
            $rules['rs_entorigen_id'] = 'required|numeric|exists:rs_entidades,id';
        }

        return $rules;
    }
}
