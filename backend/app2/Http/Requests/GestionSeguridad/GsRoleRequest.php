<?php

namespace App\Http\Requests\GestionSeguridad;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GsRoleRequest extends FormRequest
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
            'nombre' => 'required|unique:gs_roles,nombre',
            'descripcion' => 'required'
        ];

        $rules['nombre'] = [
            'required',
            Rule::unique('gs_roles')->ignore($this->id)
        ];

        return $rules;
    }
}
