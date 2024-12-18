<?php

namespace App\Http\Requests\GestionSeguridad;

use Illuminate\Foundation\Http\FormRequest;

class GsUsuarioChangePasswordRequest extends FormRequest
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
            'current_password' => 'required',
            'password' => 'required|min:6|same:password',
            'password_confirmation' => 'required|min:6|same:password',
        ];
    }
}
