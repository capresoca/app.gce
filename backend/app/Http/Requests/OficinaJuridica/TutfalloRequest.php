<?php

namespace App\Http\Requests\OficinaJuridica;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TutfalloRequest extends FormRequest
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
            'oj_tutela_id' => 'required|exists:oj_tutelas,id',
            'instancia' => 'required|in:Primera Instancia,Segunda Instancia',
            'oj_juzgado_id' => 'required|exists:oj_juzgados,id',
            'fecha' => 'required|date',
            'fallo_integral' => 'required|in:Si,No',
            'tipo_fallo' => 'required',
            'desc_fallo' => 'required',
            'no_fallo' => 'required'
        ];

        $rules['tipo_fallo'] = [
            'required',
            Rule::in(['A Favor','En Contra'])
        ];

        return $rules;
    }
}
