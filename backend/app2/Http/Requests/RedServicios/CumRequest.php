<?php

namespace App\Http\Requests\RedServicios;

use Illuminate\Foundation\Http\FormRequest;

class CumRequest extends FormRequest
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
            'expediente' => 'required',
            'producto' => 'required',
            'titular' => 'required',
            'registro_sanitario' => 'required',
            'fecha_expedicion' => 'required',
            'fecha_vencimiento' => 'required',
            'estado_registro' => 'required',
            'cantidad_cum' => 'required' ,
            'descripcion_comercial' => 'required',
            'estado_cum' => 'required',
            'fecha_activo'=> 'required',
            'fecha_inactivo'  => 'required',
            'muestra_medica'  => 'required',
            'unidad'  => 'required',
            'atc'  => 'required',
            'descripcion_atc' => 'required',
            'via_administracion' => 'required',
            'concentracion' => 'required',
            'principio_activo' => 'required',
            'unidad_medida' => 'required',
            'cantidad' => 'required',
            'unidad_referencia' => 'required',
            'forma_farmaceutica' => 'required',
            'nombre_rol' => 'required',
            'tipo_rol' => 'required',
            'modalidad' => 'required'
        ];
    }
}
