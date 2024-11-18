<?php

namespace App\Http\Requests\RedServicios;

use Illuminate\Foundation\Http\FormRequest;

class CupsEntidadRequest extends FormRequest
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
            'cups' => 'array'
        ];

        foreach ($this->cups as $key => $cup) {
            $rules['cups.'.$key.'.id'] = 'exists:rs_cupss,id';
        }
        return $rules;
    }
}
