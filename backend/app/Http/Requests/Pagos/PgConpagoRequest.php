<?php

namespace App\Http\Requests\Pagos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PgConpagoRequest extends FormRequest
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
            'codigo' => 'required|string|max:5|unique:pg_conpagos,codigo',
            'nombre' => 'required|string|max:150',
            'tipo_concepto' => 'required|in:CXP,Notas,Traslado de Valores,Saldos Iniciales',
            'nf_niif_id' => 'required|exists:nf_niifs,id'
        ];

        if ($this->id) {
            $rules['codigo'] = [
                'required',
                Rule::unique('pg_conpagos')->ignore($this->id)
            ];
            $rules['nombre'] = [
                'required',
                Rule::unique('pg_conpagos')->ignore($this->id)
            ];
        }

        return $rules;
    }
}
