<?php

namespace App\Http\Requests\GestionSeguridad;

use Illuminate\Foundation\Http\FormRequest;

class EmpresaRequest extends FormRequest
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
            'codhabilitacion_contrib' => 'required',
            'codhabilitacion_subsid' => 'required',
            'fecha_inicial' => 'required|date',
            'firma_certificados' => 'required',
            'gerente' => 'required|exists:gn_terceros,id',
            'gn_cargo_id' => 'required|exists:th_cargos_empleados,id',
            'gn_municipio_id' => 'required|exists:gn_municipios,id',
            'gn_tercero_id' => 'required|exists:gn_terceros,id',
            'nf_niifdeficit_id' => 'required|exists:nf_niifs,id',
            'nf_niifganacias_id' => 'required|exists:nf_niifs,id',
            'nf_niifsuperavit_id' => 'required|exists:nf_niifs,id',
            'nf_tipcomdiacierre_id' => 'required|exists:nf_tipcomdiarios,id',
            'nf_tipcomdiadistribucion_id' => 'required|exists:nf_tipcomdiarios,id',
            'nf_tipcomdiajustes_id' => 'required|exists:nf_tipcomdiarios,id',
            'nf_tipcomdiahomologacion_id' => 'required|exists:nf_tipcomdiarios,id',
            'nf_tipcomdiatraslado_id' => 'required|exists:nf_tipcomdiarios,id',
            'nit_dian' => 'required|exists:gn_terceros,id',
            'rep_legal' => 'required|exists:gn_terceros,id',
            'revisor_fiscal' => 'required',
            'tarjeta_profesional' => 'required',
            'tesoreria_distrital' => 'required|exists:gn_terceros,id',
            'subgerente_servasistencial' => 'required|exists:gn_terceros,id',
            'subgerente_admin' => 'required|exists:gn_terceros,id',
            'nf_niifdebitoprovisional_id' => 'required|exists:nf_niifs,id',
            'nf_niifcreditoprovisional_id' => 'required|exists:nf_niifs,id',

        ];
    }
}
