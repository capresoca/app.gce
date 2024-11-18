<?php

namespace App\Http\Requests\RedServicios;

use App\Traits\EnumsTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContratoRequest extends FormRequest
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
        return

        [
            'regimen_atendido' => 'required|exists:as_regimenes,id',
            'pr_detrp_id' => 'required|exists:pr_detrps,id',
            'ce_proconminuta_id' => 'required|exists:ce_proconminutas,id',
            'nombre' => 'required',


        ];
    }
}
