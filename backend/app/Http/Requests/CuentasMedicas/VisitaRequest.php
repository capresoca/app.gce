<?php

namespace App\Http\Requests\CuentasMedicas;

use App\Traits\EnumsTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VisitaRequest extends FormRequest
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
            'cm_concurrencia_id' => 'required|exists:cm_concurrencias,id',
            'fecha_visita' => 'required|date',
            'evolucion' => 'required',
            'fio2' => 'nullable|numeric',
            'peep' => 'nullable|numeric',
            'frec_ve' => 'nullable|numeric',
            'uci' => 'nullable|boolean',
            'habitacion_hospitalaria' => 'required',
            'estancia_pertinente' => 'required|boolean',
            'requiere_phd' => 'nullable|boolean',
            'rs_especialidadtratante_id' => 'required|numeric|exists:rs_servicios,id',
            'rs_especialidadinterconsultante_id' => 'nullable|numeric|exists:rs_servicios,id',
            'ventilacion_asistido' => 'nullable|boolean'
        ];
        if (isset($this->condiciones_clinicas)) {
            $rules['condiciones_clinicas.*.nombre'] = 'required';
        }
        if($this->estancia_pertinente) {
            $rules['cm_causalhospitalizacion_id'] = 'required|numeric|exists:cm_tipocausalhospitalizacions,id';
            $rules['rs_cup_id'] = 'nullable|numeric|exists:rs_cupss,id';
            $rules['rs_cum_id'] = 'nullable|numeric|exists:rs_cums,id';
            $rules['rs_servicio_id'] = 'nullable|numeric|exists:rs_servicios,id';
        }

        return $rules;
    }
}
