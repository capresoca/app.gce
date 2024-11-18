<?php

namespace App\Http\Requests\Presupuesto;

use App\Traits\EnumsTrait;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class PrObligacioneRequest extends FormRequest
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
        $today = Carbon::today()->format('Y-m-d');
        $rules = [
            'fecha' => 'required|date|after_or_equal:' . $today,
            'fecha_documento' => 'required|date',
            'valor_obligacion' => 'required',
            'pr_entidad_resolucion_id' => 'required|exists:pr_entidad_resoluciones,id',
            'gn_tercero_id' => 'required|exists:gn_terceros,id',
            'estado' => 'required|in:' . implode(',', EnumsTrait::getEnumValues('pr_obligaciones','estado')),
            'detalles' => 'required'
        ];

        return $rules;
    }
}
