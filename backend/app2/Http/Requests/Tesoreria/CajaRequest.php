<?php

namespace App\Http\Requests\Tesoreria;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CajaRequest extends FormRequest
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
            'codigo' => 'required|string|max:5|unique:ts_cajas,codigo',
            'nombre' => 'required|string|max:150|unique:ts_cajas,nombre',
            'tipo' => 'required|in:Mayor,Menor',
            'fecha_apertura' => 'required|date|before_or_equal:'.today(),
            'saldo_inicial' => 'required|numeric',
            'nf_niif_id' => 'required|exists:nf_niifs,id',
            'nf_cencosto_id' => 'required|exists:nf_cencostos,id'
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
