<?php

namespace App\Http\Requests\Recaudos;

use App\Models\Aseguramiento\AsCargasRecaudo;
use Illuminate\Foundation\Http\FormRequest;

class RecuadoRequest extends FormRequest
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
            'archivo' => 'required'
        ];

        $zipName = $this->file('archivo')->getClientOriginalName();

        if(AsCargasRecaudo::where('nombre_archivos_zip',$zipName)->first()){
            $rules['archivoYaCargado'] = 'required';
        }

        return $rules;

    }

    public function messages()
    {
        return [
            'archivoYaCargado.required' => 'Este archivo ya fue procesado'
        ];
    }
}
