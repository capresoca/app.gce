<?php

namespace App\Http\Requests\ContratacionEstatal;

use Illuminate\Foundation\Http\FormRequest;

class ProconestprevioRequest extends FormRequest
{
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
        if ($this->estado !== 'Confirmado') return [];
        $rules = [
            'fecha' => 'required|date',
            'desc_necesidad' => 'required',
            'desc_tecnica' => 'required',
            'esp_tecnicas' => 'required',
            'sop_economico' => 'required',
            'alt_ejecucion' => 'required',
            'pos_riesgos' => 'required',
            'ce_tipcontratacione_id' => 'required|exists:ce_tipcontrataciones,id',
            'lugar_ejecucion' => 'required',
            'supervicion' => 'required',
            'ce_tipocontrato_id' => 'required'
            //'actividades' => 'required',
            //'forpagos' => 'required',
            //'garantias' => 'required'
        ];

        if ($this->actividades) {
            foreach ($this->actividades as $key => $actividade) {
                if (isset($actividade['ce_proconestprevio_id'])) {
                    $rules['actividades.' . $key . '.ce_proconestprevio_id'] = 'required';
                }
                if (isset($actividade['actividad']) == '') {
                    $rules['actividades.' . $key . '.actividad'] = 'required';
                }
            }
        }

        if ($this->forpagos) {
            foreach ($this->forpagos as $key => $forPago) {
                if (isset($forPago['ce_proconestprevio_id'])) {
                    $rules['forpagos.' . $key . '.ce_proconestprevio_id'] = 'required';
                }

                if (isset($forPago['tipo']) && isset($forPago['valor']) && isset($forPago['fecha'])) {
                    $rules['forpagos.' . $key . '.tipo'] = 'required|in:Porcentaje,Valor';
                    $rules['forpagos.' . $key . '.valor'] = 'required';
                    $rules['forpagos.' . $key . '.descripcion'] = 'required|date';
                }
            }
        }

        if ($this->garantias) {
            foreach ($this->garantias as $key => $garantia) {
                if (isset($forPago['ce_proconestprevio_id']) && isset($forPago['ce_garantia_id'])) {
                    $rules['forPagos.' . $key . '.ce_proconestprevio_id'] = 'required';
                    $rules['forPagos.' . $key . '.ce_garantia_id'] = 'required|exists:ce_garantiad,id';
                }
            }
        }

        return $rules;
    }
}