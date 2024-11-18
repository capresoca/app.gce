<?php

namespace App\Http\Requests\Tesoreria;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CuentasRequest extends FormRequest
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
            'ts_banco_id' => 'required|exists:ts_bancos,id',
            'codigo' => 'required|string|max:5|unique:ts_cuentas,codigo',
            'tipo_cuenta' => 'required|in:Ahorros,Corriente',
            'estado'=> 'required|in:Activo,Inactivo',
            'numero_cuenta' => 'required|string|max:20',
            'fecha_apertura' => 'required|date|before:tomorrow',
            'saldo_inicial' => 'numeric',
            'control_auto' => 'in:Si,No|nullable',
            'nf_niif_id' => 'required|exists:nf_niifs,id',
            'nf_cencosto_id' => 'exists:nf_cencostos,id',
            'gn_municipio_id' => 'required|exists:gn_municipios,id'
        ];

        if($this->id){
            $rules['codigo'] = [
                'required',
                Rule::unique('ts_cuentas')->ignore($this->id)
            ];

        }

        return $rules;
    }
}
