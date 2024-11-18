<?php

namespace App\Http\Requests\Niif;

use App\Rules\Transpocision;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConrtfRequest extends FormRequest
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
            'codigo' => 'required|string|max:5|unique:nf_conrtfs,codigo',
            'nombre' => 'required|string|max:150|unique:nf_conrtfs,nombre',
            'manejo' => 'required|in:Base,Rangos,Variable',
            'estado' => 'required'
        ];
        if($this->manejo === 'Base'){
            $rules['base_minima'] = 'required';
        }

        if($this->manejo === 'Rangos'){
            $rules['detalles'] = ['required', 'array', new Transpocision()];

        }

        if($this->id){
            $rules['codigo'] = [
                'required',
                'string',
                'max:5',
                Rule::unique('nf_conrtfs')->ignore($this->id)
            ];

            $rules['nombre'] = [
                'required',
                'string',
                'max:150',
                Rule::unique('nf_conrtfs')->ignore($this->id)
            ];
        }

        return $rules;

    }

}
