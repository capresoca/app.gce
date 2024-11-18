<?php

namespace App\Http\Requests\Presupuesto;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConceptoPresupuestoRequest extends FormRequest
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
            'codigo' => 'required|max:16|unique:pr_concepto_presupuestos,codigo',
            'nombre' => 'required'
        ];

        if($this->id){
            $rules['codigo'] = [
                'required',
                Rule::unique('pr_concepto_presupuestos')->ignore($this->id)
            ];
        }

        return $rules;
    }
}
