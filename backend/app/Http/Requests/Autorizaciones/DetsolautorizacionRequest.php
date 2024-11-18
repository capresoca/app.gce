<?php

namespace App\Http\Requests\Autorizaciones;

use Illuminate\Foundation\Http\FormRequest;

class DetsolautorizacionRequest extends FormRequest
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
            'codigo' => 'required|max:10',
            'nombre' => 'required',
            'tipo' => 'required|in:Servicios,Medicamentos,Otros',
            'cantidad' => 'required',
            'valor_unitario' => 'required',
            'valor_afiliado' => 'required',
            'valor_eps' => 'required',
            'pbs' => 'required|in:Si,No',
            'aprobado' => 'required|in:Si,No',
            'observaciones' => 'required'
        ];
    }
}
