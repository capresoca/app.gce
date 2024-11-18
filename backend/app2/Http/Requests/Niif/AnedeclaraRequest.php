<?php

namespace App\Http\Requests\Niif;

use App\Traits\EnumsTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AnedeclaraRequest extends FormRequest
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
        $estados = EnumsTrait::getEnumValues('nf_anedeclaraciones','estado');
        $rules = [
            'codigo' => 'required|string|max:5|unique:nf_anedeclaraciones,codigo',
            'nombre' => 'required|string|max:150',
            'estado' => [
                'required',
                Rule::in($estados)
            ]
        ];

        if($this->id){
            $rules['codigo'] = [
                'required',
                'string',
                'max:5',
                Rule::unique('nf_anedeclaraciones')->ignore($this->id)
            ];

            $rules['nombre'] = [
                'required',
                'string',
                'max:150',
                Rule::unique('nf_anedeclaraciones')->ignore($this->id)
            ];
        }

        return $rules;
    }
}
