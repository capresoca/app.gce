<?php

namespace App\Http\Requests\Presupuesto;

use App\Traits\EnumsTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PrRpRequest extends FormRequest
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
            'periodo' => 'required',
            'fecha' => 'required',
            'gn_tercero_id' => 'required|exists:gn_terceros,id',
            'tipo' => 'required|in:' . implode(',', EnumsTrait::getEnumValues('pr_rps','tipo')),
            'pr_entidad_resolucion_id' => 'required|exists:pr_entidad_resoluciones,id',
            'estado' => 'required|in:' .  implode(',', EnumsTrait::getEnumValues('pr_rps','estado')),
            'detalles' => 'required'
        ];

        if ($rules['tipo'] === 'Contrato') {
            $rules['ce_proconminuta_id'] = 'required|exists:ce_proconminutas,id';
        }

        if ($rules['tipo'] === 'No Aplica') {
            $rules['pr_cdp_id'] = 'required|exists:pr_cdps,id';
        }

//        dd($this->detalles);
//        foreach ($this->detalles as $key => $detalle) {
//            if(!isset($detalle['valor']) || $detalle['valor'] === null || $detalle['valor'] === 0 || $detalle['valor'] === "") $rules['detalles.' . $key . '.valor'] = 'required';
//            if ($detalle['valor'] > $detalle['valor_disponible']) $rules['detalles' . $key . '.valor'] = 'lte:' . $detalle['valor_disponible'];
//        }

//        $detalles = $this->detalles;
//
//        foreach ($this['detalles'] as $key => $detalle) {
//            if (isset($detalle['pr_detcdp_id'])) {
//                $rules['detalles.' . $key . '.pr_detcdp_id'] = 'required|numeric';
//            }
//            if (isset($detalle['pr_rubro_id'])) {
//                $rules['detalles.' . $key . '.pr_rubro_id'] = 'required|numeric';
//            }
//            if (isset($detalle['pr_tipo_gasto_id'])) {
//                $rules['detalles.' . $key . '.pr_tipo_gasto_id'] = 'required|numeric';
//            }
//
//            if(!isset($detalle['valor']) || $detalle['valor'] === null || $detalle['valor'] === 0 || $detalle['valor'] === "") $rules['detalles.' . $key . '.valor'] = 'required';
//            if ($detalle['valor'] > $detalle['valor_disponible']) $rules['detalles' . $key . '.valor'] = 'lte:' . $detalle['valor_disponible'];
//        }
//
//        $messages = [
//            'detalles.1.pr_detcdp_id.required' => 'Se debe agregar al menos una cuenta para acreditar y una para debitar',
//            'detalles.required' => 'Se debe agregar al menos un detalle para el registro presupuestal.',
//        ];
//
//        $validator = Validator::make($this->toArray(), $rules, $messages);
//
//        if($validator->fails()){
//            throw new ValidationException($validator->errors());
//        }
        return $rules;
    }
}
