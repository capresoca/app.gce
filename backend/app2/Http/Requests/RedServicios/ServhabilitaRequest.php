<?php

namespace App\Http\Requests\RedServicios;

use Illuminate\Foundation\Http\FormRequest;

class ServhabilitaRequest extends FormRequest
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
            'servicios' => 'array'
        ];

        foreach ($this->servicios as $key => $servicio) {
            $rules['servicios.'.$key] = 'exists:rs_servicios,id';
        }

        return $rules;
    }
}