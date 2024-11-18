<?php

namespace App\Http\Requests\Presupuesto;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EntidadResolucionRequest extends FormRequest
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
            'codigo' => 'required|max:16|unique:pr_entidad_resoluciones,codigo',
            'periodo' => 'required|unique:pr_entidad_resoluciones,periodo',
            'nombre' => 'required',
            'gn_tercero_id' => 'required',
            'resolucion' => 'required',
            'valor' => 'required',
            'identificacion_rep_legal' => 'required',
            'representante_legal' => 'required',
            'identificacion_jefe_pr' => 'required',
            'jefe_presupuesto' => 'required',
            'identificacion_jefe_financiero' => 'required',
            'jefe_financiero' => 'required'
        ];

        if($this->id){
            $rules['codigo'] = [
                'required',
                Rule::unique('pr_entidad_resoluciones')->ignore($this->id)
            ];
        }

        $rules['periodo'] = [
            'required',
            Rule::unique('pr_entidad_resoluciones')->ignore($this->id)
        ];

        return $rules;
    }
}
