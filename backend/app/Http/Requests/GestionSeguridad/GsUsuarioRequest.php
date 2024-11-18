<?php

namespace App\Http\Requests\GestionSeguridad;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GsUsuarioRequest extends FormRequest
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
            'identification' => 'required|max:15|unique:gs_usuarios,identification',
            'name' => 'required',
            'email' => 'required|email|unique:gs_usuarios,email',
            'phone' => 'required',
            'tipo' => 'required|in:Funcionario,Entidad,Afiliado,Pagador'
        ];

        $rules['identification'] = [
            'required',
            Rule::unique('gs_usuarios')->ignore($this->id)
        ];
        $rules['email'] = [
            'required',
            Rule::unique('gs_usuarios')->ignore($this->id)
        ];

        if ($this->tipo === 'Entidad') {
            $rules['rs_entidad_id'] = 'required|exists:rs_entidades,id';
        }

        return $rules;
    }
}
