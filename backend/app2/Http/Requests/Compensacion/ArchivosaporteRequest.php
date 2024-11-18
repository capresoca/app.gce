<?php

namespace App\Http\Requests\Compensacion;

use App\Models\Compensacion\CpArchivosaporte;
use Illuminate\Foundation\Http\FormRequest;

class ArchivosaporteRequest extends FormRequest
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
            'financiero' => 'required|file',
            'pila' => 'required|file'
        ];

        if(!mb_detect_encoding($this->file('financiero')->get())){
            $rules['codificacion'] = 'required';
        }


        $exists_financiero = CpArchivosaporte::whereHas('archivoFinanciero',function ($query){
            $query->where('nombre',$this->file('financiero')->getClientOriginalName());
        })->first();

        $exists_pila = CpArchivosaporte::whereHas('archivoPila',function ($query){
            $query->where('nombre',$this->file('pila')->getClientOriginalName());
        })->first();

        if(substr($this->file('financiero')->getClientOriginalName(),0,10) != 'FINANCIERO'){
            $rules['financiero_invalido'] = 'required';
        }

        if(substr($this->file('pila')->getClientOriginalName(),0,4) != 'PILA'){
            $rules['pila_invalido'] = 'required';
        }

        if(substr($this->file('financiero')->getClientOriginalName(),-18) !=  substr($this->file('pila')->getClientOriginalName(),-18)){
            $rules['archivos_diferentes'] = 'required';
        }

        if($exists_pila){
            $rules['pila_procesado'] = 'required';
        }

        if($exists_financiero){
            $rules['financiero_procesado'] = 'required';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'codificacion.required' => 'La codificación debe ser UTF8',
            'financiero_invalido.required' => 'Archivo Financiero invalido',
            'pila_invalido.required' => 'Archivo Financiero invalido',
            'pila_procesado.required' => 'El archivo pila ya fue procesado',
            'financiero_procesado.required' => 'El archivo financiero ya fue procesado',
            'archivos_diferentes.required' => 'Los archivos no pertenecen a la misma fecha'
        ];
    }
}
