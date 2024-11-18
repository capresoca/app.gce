<?php

namespace App\Http\Requests\RedServicios;

use Illuminate\Foundation\Http\FormRequest;

class Cie10Request extends FormRequest
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
            'codigo' => 'required|max:4',
            'codigo_tres' => 'required|max:3',
            'descripcion' => 'required',
            'genero' => 'required|in:A,M,F',
            'edad_min' => 'required|max:10',
            'edad_max' => 'required|max:10'
        ];
    }
}
