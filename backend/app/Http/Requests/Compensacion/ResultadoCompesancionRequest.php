<?php

namespace App\Http\Requests\Compensacion;

use App\Models\Compensacion\CpArchivosaporte;
use Illuminate\Foundation\Http\FormRequest;

class ResultadoCompesancionRequest extends FormRequest
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
            'acx' => 'nullable|file',
            'abx' => 'nullable|file',
            'fecha_resultado' => 'required|date'
        ];

        if($this->abx && !mb_detect_encoding($this->file('abx')->get())){
            $rules['codificacion_abx'] = 'required';
        }

        if($this->acx && !mb_detect_encoding($this->file('acx')->get())){
            $rules['codificacion_acx'] = 'required';
        }


        if($this->abx && substr($this->file('abx')->getClientOriginalName(),0,3) != 'ABX'){
            $rules['abx_invalido'] = 'required';
        }

        if($this->acx && substr($this->file('acx')->getClientOriginalName(),0,3) != 'ACX'){
            $rules['acx_invalido'] = 'required';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'codificacion_abx.required' => 'La codificación del archivo abx debe ser UTF8',
            'codificacion_acx.required' => 'La codificación del archivo acx debe ser UTF8',
            'acx_invalido.required' => 'Archivo ACX invalido',
            'abx_invalido.required' => 'Archivo ABX invalido'
        ];
    }
}
