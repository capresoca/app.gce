<?php

namespace App\Http\Requests\CuentasMedicas;

use App\Traits\EnumsTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConhallazgosRequest extends FormRequest
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
        $clases = EnumsTrait::getEnumValues('cm_conhallazgos','clase');
        return [
            'cm_convisita_id' => 'required|numeric|exists:cm_convisitas,id',
            'clase' => ['required', Rule::in($clases)],
            'observaciones' => 'required'
        ];
    }
}
