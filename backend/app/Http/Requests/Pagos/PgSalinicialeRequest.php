<?php

namespace App\Http\Requests\Pagos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PgSalinicialeRequest extends FormRequest
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
            'pg_proveedore_id' => 'required|exists:pg_proveedores,id',
            'nf_niif_id' => 'required|exists:nf_niifs,id',
            'tipo' => 'required|in:CXP,Anticipo',
            'fecha' => 'required',
            'valor' => 'required'
        ];

        if ($this->tipo === 'CXP') {
            $rules['fecha_documento'] = 'required|date';
            $rules['fecha_vencimiento'] = 'required|date';
            $rules['no_documento'] = 'required|unique:pg_saliniciales,no_documento';

            $rules['no_documento'] = [
                'required',
                Rule::unique('pg_saliniciales')->ignore($this->id)
            ];
        } else {
            $rules['fecha_anticipo'] = 'required|date';
        }

        if ($this->estado === 'Anulado') {
            $rules['detalle_anulacion'] = 'required';
        }

        return $rules;
    }
}
