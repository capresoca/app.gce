<?php

namespace App\Http\Requests\Cartera;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaldosinicialesRequest extends FormRequest
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
            'consecutivo' => 'required|unique:cr_saldosiniciales,consecutivo',
            'tipo_saldo' => 'required',
            'cr_cliente_id' => 'required',
            'gn_tercero_id' => 'required',
            'cr_vendedor_id' => 'required',
            'nf_niif_id' => 'required',
            'saldo_factura' => 'required'
        ];

        if($this->id){
            $rules['consecutivo'] = [
                'required',
                Rule::unique('cr_saldosiniciales')->ignore($this->id)
            ];
        }

        return $rules;
    }
}
