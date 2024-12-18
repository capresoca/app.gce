<?php

namespace App\Http\Requests\RedServicios;

use App\Traits\EnumsTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NovcontratoRequest extends FormRequest
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
        $tipos = EnumsTrait::getEnumValues('rs_novcontratos','tipo');
        return  [
            'tipo' => [
                'required',
                Rule::in($tipos)
            ],
            'fecha' => 'required|date',
        ];

    }
}
