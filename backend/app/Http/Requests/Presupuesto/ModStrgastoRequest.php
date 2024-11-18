<?php

namespace App\Http\Requests\Presupuesto;

use App\Traits\EnumsTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ModStrgastoRequest extends FormRequest
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
            'periodo' => 'required',
            'fecha' => 'required',
            'documento' => 'required',
            'detalle_modificacion' => 'required',
            'estado' => 'required|in:' . implode(',', EnumsTrait::getEnumValues('pr_mod_strgastos','estado')),
            'gs_strgasto_id' => 'required|exists:pr_strgastos,id'
        ];

//        $rules['periodo'] = [
//            'required',
//            'string',
//            'max:4',
//            Rule::unique('pr_mod_strgastos')->ignore($this->id)
//        ];

        if ($rules['estado'] === 'Anulada') {
            $rules['concepto_anulacion'] = 'required';
        }

        return $rules;
    }
}
