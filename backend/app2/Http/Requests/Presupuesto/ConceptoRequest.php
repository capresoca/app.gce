<?php

namespace App\Http\Requests\Presupuesto;

use App\Models\Presupuesto\PrNiveles;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConceptoRequest extends FormRequest
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
            'codigo' => [
                'required',
                Rule::unique('pr_conceptos')->where(function ($query) {
                    return $query->where('pr_estructura_id', 1);
                })->ignore($this->id)
            ],
            'nombre' => 'required',
            'pr_nivel_id' => 'required|exists:pr_niveles,id',
            'tipo_rubro' => 'required|in:Gastos,Ingresos'
        ];


        $nivel = PrNiveles::find($this->pr_nivel_id);

        if($nivel && $nivel->nivel > 1){
            $rules['pr_concepto_id'] = 'required|exists:pr_conceptos,id';
        }
        return $rules;
    }
}
