<?php

namespace App\Http\Requests\AtencionUsuario;

use App\Models\Aseguramiento\AsAfiliado;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class IncapacidadRequest extends FormRequest
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
        $rules =[
            'as_afiliados_id' => 'required|exists:as_afiliados,id',
            'as_afiliado_pagador_id' => 'required|exists:as_afiliado_pagador,id',
            'au_tipincapacidades_id' => 'required|exists:au_tipincapacidades,id',
            'fecha_inicio' => 'date',
            'dias_incapacidad' => 'required',
            'base_liquidacion' => '',
            'pagar_a' => 'in:Afiliado,Aportante',
//            'fecha' => 'required|date',

//            'incapacidad' => $this->archivo_incapacidad_id ? '': 'required|file',
//            'histclinica' => $this->histclinica_id ? '': 'required|file',
//            'archivo_identificacion' => $this->archivo_identificacion_id ? '': 'required|file',
//            'certbanco' => $this->certbanco_id ? '': 'required|file',
//
//            'archivo_incapacidad_id' => !$this->incapacidad ? '': 'required|exists:gn_archivos,id',
//            'histclinica_id' => !$this->histclinica ? '': 'required|exists:gn_archivos,id',
//            'archivo_identificacion_id' => !$this->archivo_identificacion ? '': 'required|exists:gn_archivos,id',
//            'certbanco_id' => !$this->certbanco ? '': 'required|exists:gn_archivos,id',

            //'gs_usuarios_id' => 'required|exists:gs_usuarios,id',
            //'usutramita_id' => 'required|exists:gs_usuarios,id',
            'fecha_tramite' => 'nullable|date',

        ];

        if($this->au_tipincapacidades_id === 1){
            $rules['dias_gestacion'] = 'required|numeric';
            $rules['parto_multiple'] = 'boolean';
        }
        $afiliado = AsAfiliado::find($this->as_afiliados_id);
        if($afiliado){
            $fecha_inicio = Carbon::parse($this->fecha_inicio);
            $fecha_afiliacion = Carbon::parse($afiliado->fecha_afiliacion);

            if(!$fecha_inicio->greaterThan($fecha_afiliacion->addMonth())){
                $rules['mes_no_cumplido'] = 'required';
            }
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'mes_no_cumplido.required' => 'El afiliado aun no completa un mes de afiliacion'
        ];
    }
}
