<?php

namespace App\Http\Requests\Autorizaciones;

use Illuminate\Foundation\Http\FormRequest;

class SolautorizacionRequest extends FormRequest
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
        return [
            'tipo_aprobacion' => 'required|in:Pertinencia Medica,Derechos del Afiliado',
            'rs_plancobertura_id' => 'required|exists:rs_plancoberturas,id',
            'fecha' => 'required',
            'fecha_ordmedica' =>'required|date',
            'as_afiliado_id' => 'required|exists:as_afiliados,id',
            'as_regimen_id' => 'required|exists:as_regimenes,id',
            'tipo_origen' => 'required|in:Ambulatorio,Hospitalario,Urgencias',
            'rs_entorigen_id' => 'required|exists:rs_entidades,id',
            'rs_cie10_id' => 'required|exists:rs_cie10s,id',
            'rs_entdestino_id' => 'required|exists:rs_entidades,id',
            'au_modservicio_id' => 'required|exists:au_modservicios,id',
            'au_servicio_id' => 'required|exists:au_servicios,id',
            'alto_costo' => 'required|in:Si,No',
            'tutela' => 'required|in:Si,No',
            'tipo_autorizacion' => 'required|in:Ambulatoria,Urgencia,Hospitalaria,Referencia,Alto Costo,Tutela',
            'observaciones' => 'required',
            'estado' => 'required|in:Registrada,En Estudio,Aprobada,Aprobada Parcialmente,Negada,Anulada'
        ];
//        'au_autorizacion_id' => 'required|exists:au_autorizaciones,id',
    }
}
