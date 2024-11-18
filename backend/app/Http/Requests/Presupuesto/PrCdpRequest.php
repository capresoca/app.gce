<?php

namespace App\Http\Requests\Presupuesto;

use App\Traits\EnumsTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PrCdpRequest extends FormRequest
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
        $fecha_vence = $this->fecha_vence;
        $fecha = $this->fecha;
        $rules = [
            'objecto' => 'required',
            'valor_cdp' => 'required',
            'fecha' => 'required|before:' . $fecha_vence,
            'fecha_vence' => 'required|after_or_equal:' . $fecha,
            'pr_strgasto_id' => 'required|exists:pr_strgastos,id',
            'pr_entidadresolucion_id' => 'required|exists:pr_entidad_resoluciones,id',
            'estado' => 'required|in:' . implode(',', EnumsTrait::getEnumValues('pr_cdps', 'estado')),
            'vigente' => 'required',
            'detalles' => 'required'
        ];

        $detalles = $this->toArray($this->detalles);

        if ($detalles) {
            foreach ($detalles as $key => $detalle) {
                if (isset($detalle['pr_detstrgasto_id'])) {
                    $rules['detalles.' . $key . '.pr_detstrgasto_id'] = 'required|numeric';
                }
                if (isset($detalle['pr_rubro_id'])) {
                    $rules['detalles.' . $key . '.pr_rubro_id'] = 'required|numeric';
                }
                if (isset($detalle['pr_tipo_gasto_id'])) {
                    $rules['detalles.' . $key . '.pr_tipo_gasto_id'] = 'required|numeric';
                }
                if(isset($detalle['valor']) && $detalle['valor'] === null) $rules['detalles.' . $key . '.valor'] = 'required';
            }
        }

        return $rules;
//        $messages = [
//            'detalles.required' => 'Los rubros son requeridos.',
//        ];
//
//        $validator = Validator::make( $data, $rules, $messages);
//
//        if($validator->fails()){
//            throw new ValidationException($validator->errors());
//        }
    }
}
