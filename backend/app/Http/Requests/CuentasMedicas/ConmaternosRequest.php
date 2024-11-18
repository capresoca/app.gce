<?php

namespace App\Http\Requests\CuentasMedicas;

use App\Traits\EnumsTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConmaternosRequest extends FormRequest
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
        $condiciones_egreso = EnumsTrait::getEnumValues('cm_conmaternos','condicion_egreso');
        return [
            'cm_concurrencia_id' => 'required|numeric|exists:cm_concurrencias,id',
            'fecha_parto' => 'nullable|date',
            'tipo_parto' => 'required|in:Único,Múltiple',
            'via_parto' => 'required|in:Vaginal,Cesárea',
            // 'controles' => 'nullable|numeric',
            // 'edad_gestacion' => 'nullable|numeric',
            'dx_nacimiento' => 'nullable|exists:rs_cie10s,id',
            'dx_relacionado' => 'nullable|exists:rs_cie10s,id',
            'cm_complicacionparto_id' => 'nullable|exists:cm_complicacionpartos,id',
            // 'peso_recien_nacido' => 'nullable|numeric',
            // 'perimetro_cefalico' => 'nullable|numeric',
            // 'perimetro_abdominal' => 'nullable|numeric',
            'cm_complicacionneonato_id' => 'nullable|exists:cm_complicacionneonatos,id',
            'condicion_egreso' => ['required',Rule::in($condiciones_egreso)]
            // 'historia_clinica_id' => 'nullable|numerica|exists:gn_archivos,id'
        ];
    }
}
