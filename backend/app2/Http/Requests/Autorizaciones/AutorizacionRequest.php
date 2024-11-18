<?php

namespace App\Http\Requests\Autorizaciones;

use App\Traits\EnumsTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AutorizacionRequest extends FormRequest
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
        $tipos_origen = EnumsTrait::getEnumValues('au_autorizaciones','tipo_origen');

        $rules = [
            'as_afiliado_id' => 'required|exists:as_afiliados,id',
            'afiliado.as_regimene_id' => 'required|exists:as_regimenes,id',
            'entidad_origen_id' => 'required|exists:rs_entidades,id',
            'tipo_origen' => [
                'required',
                Rule::in($tipos_origen)
            ],
            'cie10_principal_id' => 'required|exists:rs_cie10s,id',
            'cie10_rel1_id' => 'nullable|exists:rs_cie10s,id',
            'cie10_rel2_id' => 'nullable|exists:rs_cie10s,id',
            'au_modservicio_id' => 'required|exists:au_modservicios,id',
            'rs_contrato_id' => 'required|exists:rs_planescoberturas,id',
            'rs_servicio_id' => 'required|exists:rs_servicios,id',
            'estado' => 'required|in:Registrada,Confirmada'
        ];

        foreach ($this->detalles as $key => $detalle){
            $rules['detalles.'.$key.'.cantidad_solicitada'] = 'numeric|required';

            if(!$this->tieneCumCupOtro($detalle)){
                $rules['detalles.'.$key.'.cum_cup_otro'] = 'required';
                continue;
            }

            if(isset($detalle['rs_cum_id']) && $detalle['rs_cum_id']){
                $rules['detalles.'.$key.'.rs_cum_id'] = 'nullable|exists:rs_cumcontratados,id';
            }

            if(isset($detalle['rs_cups_id']) && $detalle['rs_cups_id']){
                $rules['detalles.'.$key.'.rs_cum_id'] = 'nullable|exists:rs_cupscontratados,id';
            }

        }

        return $rules;
    }

    public function tieneCumCupOtro($detalle)
    {
        return  (
                    array_key_exists('rs_cum_id', $detalle)    ||
                    array_key_exists('rs_cups_id', $detalle)   ||
                    array_key_exists('rs_otros_id', $detalle)
                ) &&
                (
                    $detalle['rs_cum_id']   ||
                    $detalle['rs_cups_id']  ||
                    $detalle['rs_otros_id']
                )
                ;
    }

    public function messages()
    {
        return [
            'detalles.*.cum_cup_otro.required' => 'Se debe agregar un Cup, Cum, u Otro servicio'
        ];
    }
}


