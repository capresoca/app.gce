<?php

namespace App\Http\Requests\RedServicios;

use Illuminate\Foundation\Http\FormRequest;

class AsignaCSVRequest extends FormRequest
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
            'servicios_habilitados.*' => 'required|numeric|exists:rs_servhabilitados,id',
            'csv_afiliados' => 'required|file|mimes:txt'
        ];

    }
}
