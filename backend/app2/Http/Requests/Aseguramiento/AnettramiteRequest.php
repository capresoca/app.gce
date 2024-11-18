<?php

namespace App\Http\Requests\Aseguramiento;

use Illuminate\Foundation\Http\FormRequest;

class AnettramiteRequest extends FormRequest
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
        $rules =  [
            'as_tipanexo_id' => 'required|exists:as_tipanexos,id',
            'as_afiliado_id' => 'required|exists:as_afiliados,id',
            'anexo' => 'required|file|mimes:pdf,jpeg,bmp,png,'
        ];

        if (!$this->as_formafiliacion_id) {
            $rules['as_formtrasmov_id'] = 'required|exists:as_formtrasmovs,id';

        }

        if(!$this->as_formtrasmov_id){
            $rules['as_formafiliacion_id'] = 'required|exists:as_formafiliaciones,id';
        }


        if($this->id){
            $rules['anexo'] = '';
        }

        return $rules;
    }
}
