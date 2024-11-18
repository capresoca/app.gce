<?php

namespace App\Http\Requests\CuentasMedicas;

use Illuminate\Foundation\Http\FormRequest;

class Concie10sRequest extends FormRequest
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
            'cm_convisita_id' => 'required|numeric|exists:cm_convisitas,id',
            'dx_relacionado' => 'required|numeric|exists:rs_cie10s,id',
            'fecha_desde' => 'date',
            'cm_manglosa_id' => 'numeric|exists:cm_manglosas,id'
        ];
    }
}
