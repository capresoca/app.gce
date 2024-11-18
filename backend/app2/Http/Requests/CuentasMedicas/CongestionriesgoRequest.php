<?php

namespace App\Http\Requests\CuentasMedicas;

use App\Traits\EnumsTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CongestionriesgoRequest extends FormRequest
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
        $eventos_centinela = EnumsTrait::getEnumValues('cm_congestionriesgos','evento_centinela');
        $rutas_atencion = EnumsTrait::getEnumValues('cm_congestionriesgos', 'ruta_atencion');
        return [
            'cm_concurrencia_id' => 'required|numeric|exists:cm_concurrencias,id',
            'evento_centinela' => ['nullable', Rule::in($eventos_centinela)],
            'cm_eventosaludpublica_id' => 'nullable|numeric|exists:cm_eventosaludpublicas,id',
            'cm_patologiatrazadora_id' => 'nullable|numeric|exists:cm_patologiatrazadoras,id',
            'cm_comppatologia_id' => 'nullable|numeric|exists:cm_comppatologias,id',
            'ruta_atencion' => ['nullable', Rule::in($rutas_atencion)]
        ];
    }
}
