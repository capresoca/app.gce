<?php

namespace App\Http\Requests\CuentasMedicas;

use App\Traits\EnumsTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConcumsRequest extends FormRequest
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
        $causas = EnumsTrait::getEnumValues('cm_concums','causa');
        return [
            'cantidad' => 'required|numeric',
            'cm_convisita_id' => 'required|numeric|exists:cm_convisitas,id',
            'rs_cum_id' => 'required|numeric|exists:rs_cums,id',
            'causa' => ['required', Rule::in($causas)],
            'fecha' => 'required|date',
            'cm_manglosa_id' => 'required|numeric|exists:cm_manglosas,id'
        ];
    }
}
