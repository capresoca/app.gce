<?php

namespace App\Http\Requests\AtencionUsuario;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TipincapacidadRequest extends FormRequest
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
            'codigo' => [
                'required',
                Rule::unique('au_tipincapacidades')->ignore($this->id)
            ],
            'tipo' => 'required',
            'pocentaje' => 'numeric',
            'pr_rubro_id' => 'nullable|exists:pr_rubros,id',
            'max_dias' => 'required|numeric',
            'tipo_dias' => 'required|in:Hábiles,Corrientes'
        ];
    }
}
