<?php

namespace App\Http\Requests\Aseguramiento;

use Illuminate\Foundation\Http\FormRequest;

class DecautramiteRequest extends FormRequest
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
            'as_declaracione_id' => 'required|exists:as_declaraciones,id',
            'anexo' => 'file|mimes:pdf,jpeg,bmp,png'
        ];

        if (!$this->as_formafiliacion_id) {
            $rules['as_formtrasmov_id'] = 'required|exists:as_formtrasmovs,id';

        }

        if(!$this->as_formtrasmov_id){
            $rules['as_formafiliacion_id'] = 'required|exists:as_formafiliaciones,id';
        }

        return $rules;
    }
}
