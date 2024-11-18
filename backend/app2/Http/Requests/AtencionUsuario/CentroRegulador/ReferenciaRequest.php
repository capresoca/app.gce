<?php

namespace App\Http\Requests\AtencionUsuario\CentroRegulador;

use App\Traits\EnumsTrait;
use Illuminate\Foundation\Http\FormRequest;

class ReferenciaRequest extends FormRequest
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
            'fecha_orden' => 'required',
            'fecha_solicitud' => 'required',
            'as_afiliado_id' => 'required|exists:as_afiliados,id',
            'entidad_origen_id' => 'required|exists:rs_entidades,id',
            'as_regimen_id' => 'required|exists:as_regimenes,id',
            'tipo_origen' => 'required|in:'.implode(',', EnumsTrait::getEnumValues('au_referencias','tipo_origen')),
            'au_modservicio_id' => 'required|exists:au_modservicios,id',
            'cie10_ingreso_id' => 'required|exists:rs_cie10s,id',
            'rs_servicio_id' => 'required|exists:rs_servicios,id',
            'observaciones' => 'required',
            'estado' => 'required|in:'.implode(',', EnumsTrait::getEnumValues('au_referencias','estado')),
            'archivo_uno' => 'required'
//            'oj_tutela_id' => 'required|exists:oj_tutelas,id',
//            'entidad_egreso_id' => 'required|exists:rs_entidades,id',
//            'cie10_egreso_id' => 'required|exists:rs_cie10s,id',
//            'fecha_egreso' => 'required'
        ];

        return $rules;
    }
}
