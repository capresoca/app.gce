<?php

namespace App\Http\Requests\RedServicios;

use Illuminate\Foundation\Http\FormRequest;

class PortabilidadRequest extends FormRequest
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
            'as_afiliado_id'        => 'required|exists:as_afiliados,id',
            'fecha_solicitud'       => 'required|date',
            'fecha_inicio'          => 'required|date',
            'fecha_fin'             => 'required|date',
            'munreceptor_id'        => 'required|exists:gn_municipios,id',
            //'entidad_id'            => 'required|exists:rs_entidades,id',
            'direccion'             => 'required|string|max:100',
            'telefono'              => 'required'
            //'gn_archivo_id'         => $this['gn_archivo_id'] !== null ? 'exists:gn_archivos,id' : 'nullable',
            //'estado'                => 'required|in:Registrado,Negado,Aceptado',
//            'usuario_tramita_id'    => 'exists:gs_usuarios,id',
//            'fecha_tramite'         => 'date'
        ];
    }
}
