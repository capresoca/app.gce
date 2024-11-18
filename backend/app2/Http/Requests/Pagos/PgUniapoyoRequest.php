<?php

namespace App\Http\Requests\Pagos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PgUniapoyoRequest extends FormRequest
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
            'codigo' => 'required|string|max:5|unique:pg_uniapoyos,codigo',
            'nombre' => 'required|string|max:150|unique:pg_uniapoyos,nombre',
            'nf_cencosto_id' => 'required|exists:nf_cencostos,id'
        ];

        if ($this->id) {
            $rules['codigo'] = [
                'required',
                Rule::unique('pg_uniapoyos')->ignore($this->id)
            ];
            $rules['nombre'] = [
                'required',
                Rule::unique('pg_uniapoyos')->ignore($this->id)
            ];
        }

        return $rules;
    }
}
