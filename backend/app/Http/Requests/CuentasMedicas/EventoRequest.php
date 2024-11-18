<?php

namespace App\Http\Requests\CuentasMedicas;

use App\Traits\EnumsTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EventoRequest extends FormRequest
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
        $tipo = EnumsTrait::getEnumValues('cm_coneventos','tipo');
        return [
            'cm_convisita_id' => 'nullable|exists:cm_convisitas,id',
            'tipo' => ['required',Rule::in($tipo)],
            'fecha' => 'nullable|date',
            'salud_publica' => 'nullable|boolean',
            'cm_concurrencia_id' => 'required|numeric|exists:cm_concurrencias,id',
            'cm_eventoadversoeps_id' => 'nullable|exists:cm_eventoadversoepss,id',
            'cm_eventoadversoips_id' => 'nullable|exists:cm_eventoadversoipss,id',
            'fecha_notificacion' => 'nullable|date',
            'cm_manglosa_id' => 'nullable|exists:cm_manglosas,id'
        ];
    }
}
