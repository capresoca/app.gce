<?php

namespace App\Http\Requests\Cartera;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConceptosRequest extends FormRequest
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
            'codigo' => 'required|max:5',
            'nombre' => 'required|max:150',
            'tipo_concepto' => 'required|in:Cuenta por Cobrar,Notas',
            'nf_niif_id' => 'required|exists:nf_niifs,id',
            'maneja_tercero' => 'required|in:Si,No',
            'afecta_cartera' => 'required|in:Si,No',
            'afecta_bancos' => 'required|in:Si,No',
            'glosas' => 'required|in:Si,No',
        ];

        return $rules;
    }
}