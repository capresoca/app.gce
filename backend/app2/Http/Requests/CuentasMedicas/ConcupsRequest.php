<?php

namespace App\Http\Requests\CuentasMedicas;

use App\Traits\EnumsTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConcupsRequest extends FormRequest
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
        $tipos_servicios = EnumsTrait::getEnumValues('cm_concups','tipo_servicio');
        $causas = EnumsTrait::getEnumValues('cm_concups', 'causa');
        $causas_especificas = EnumsTrait::getEnumValues('cm_concups','causa_especifica');
        return [
            'cantidad' => 'required|numeric',
            'rs_cup_id' => 'required|numeric|exists:rs_cupss,id',
            'cm_convisita_id' => 'required|numeric|exists:cm_convisitas,id',
            'tipo_servicio' => ['required', Rule::in($tipos_servicios)],
            'causa' => ['required', Rule::in($causas)],
            'causa_especifica' => ['required', Rule::in($causas_especificas)],
            'fecha_desde' => 'required|date',
            'cm_manglosa_id' => 'required|numeric|exists:cm_manglosas,id'
        ];
    }
}
