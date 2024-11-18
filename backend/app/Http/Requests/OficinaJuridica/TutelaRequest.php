<?php

namespace App\Http\Requests\OficinaJuridica;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TutelaRequest extends FormRequest
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
//        dd($this->archivo);
        $rules = [
            'gn_tipdocentidad_id' => 'required|exists:gn_tipdocidentidades,id',
            'identificacion' => 'required',
            'nombre' => 'required',
//            'as_afiliado_id' => 'required|exists:as_afiliados,id',
            'oj_juzgado_id' => 'required|exists:oj_juzgados,id',
            'no_tutela' => 'required',
            'fecha' => 'required|date',
            'oj_pretencion_id' => 'required|exists:oj_pretenciones,id',
            'desc_pretencion' => 'required',
            'tipo_tutela' => 'required|in:Administrativa,POS,NO-POS',
            'med_provisional' => 'required|in:Si,No',
            'estado' => 'in:Recibida,Contestada,Fallo,Impugnada,Desacato',
            'fecha_notificacion' => 'required|date',
            'gn_departamento_id' => 'required|exists:gn_departamentos,id',
            'incidente_desacato' => 'required|in:Si,No'
        ];

        $rules['estado'] = [
            Rule::in(['Recibida', 'Contestada', 'Fallo', 'Impugnada', 'Desacato'])
//            Rule::in(['Recibida','Asignada','Respondida','Fallada','Impugnada','Desacato'])
        ];

        $rules['tipo_tutela'] = [
            'required',
            Rule::in(['Administrativa','POS','NO-POS'])
        ];

        $rules['med_provisional'] = [
            'required',
            Rule::in(['Si','No'])
        ];

        $rules['incidente_desacato'] = [
            'required',
            Rule::in(['Si','No'])
        ];

        return $rules;

    }
}
