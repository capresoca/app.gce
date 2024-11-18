<?php

namespace App\Http\Requests\Autorizaciones;

use Illuminate\Foundation\Http\FormRequest;

class AutsolicitudRequest extends FormRequest
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
            'au_autorizacion_id' => 'required|exists:au_autorizaciones,id',
            'id' => 'required|exists:au_autsolicitudes,id'
        ];
        foreach ($this->detalles as $key => $detalle) {
            $rules['detalles.' . $key . '.cantidad_solicitada'] = 'numeric|required';

            if (!$this->tieneCumCupOtro($detalle)) {
                $rules['detalles.' . $key . '.cum_cup_otro'] = 'required';
                continue;
            }

            if (isset($detalle['rs_cum_id'])) {
                $rules['detalles.' . $key . '.rs_cum_id'] = 'nullable|exists:rs_cumcontratados,id';
            }

            if (isset($detalle['rs_cups_id'])) {
                $rules['detalles.' . $key . '.rs_cups_id'] = 'nullable|exists:rs_cupscontratados,id';
            }

            if (isset($detalle['rs_otros_id'])) {
                $rules['detalles.' . $key . '.rs_otros_id'] = 'nullable|exists:rs_otroscontratados,id';
            }

        }
        return $rules;
    }

    public function tieneCumCupOtro($detalle)
    {
        return (
                array_key_exists('rs_cum_id', $detalle) ||
                array_key_exists('rs_cups_id', $detalle) ||
                array_key_exists('rs_otros_id', $detalle)
            ) &&
            (
                $detalle['rs_cum_id'] ||
                $detalle['rs_cups_id'] ||
                $detalle['rs_otros_id']
            );
    }
}
