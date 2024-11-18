<?php

namespace App\Http\Requests\CuentasMedicas;

use Illuminate\Foundation\Http\FormRequest;

class ConinsumosRequest extends FormRequest
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
        return [
            'codigo' => 'required',
            'descripcion' => 'required',
            'cantidad' => 'required|numeric',
            'cm_convisita_id' => 'required|numeric|exists:cm_convisitas,id',
            'fecha' => 'required|date',
            'cm_manglosa_id' => 'required|numeric|exists:cm_manglosas,id'
        ];
    }
}
