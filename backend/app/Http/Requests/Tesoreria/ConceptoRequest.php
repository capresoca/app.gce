<?php

namespace App\Http\Requests\Tesoreria;

use App\Traits\EnumsTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConceptoRequest extends FormRequest
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
        $tipos = EnumsTrait::getEnumValues('ts_conceptos','tipo');
        $rules = [
            'codigo' => 'required|string|max:5|unique:ts_conceptos,codigo',
            'nombre' => 'required|string|max:150|unique:ts_conceptos,nombre',
            'tipo' => Rule::in($tipos),
            'nf_niif_id' => 'required|exists:nf_niifs,id',
        ];
        if($this->id){
            $rules['codigo'] = [
                'required',
                Rule::unique('ts_bancos')->ignore($this->id)
            ];


            $rules['nombre'] = [
                'required',
                Rule::unique('ts_bancos')->ignore($this->id)
            ];
        }

        return $rules;
    }
}
