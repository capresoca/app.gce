<?php

namespace App\Http\Requests\CuentasMedicas;

use App\Traits\EnumsTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConaltocostoRequest extends FormRequest
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
        $tipos = EnumsTrait::getEnumValues('cm_conaltocostos','tipo');
        return [
            'cm_concurrencia_id' => 'required|numeric|exists:cm_concurrencias,id',
            'tipo' => ['required', Rule::in($tipos)],
            'rs_cie10_id' => 'required|numeric|exists:rs_cie10s,id',
        ];
    }
}
