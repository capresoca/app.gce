<?php

namespace App\Http\Requests\ContratacionEstatal;

use App\Traits\EnumsTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MinutaRequest extends FormRequest
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
        $estados = EnumsTrait::getEnumValues('ce_proconminutas','estado');

        $rules = [
            'fecha_contrato' => 'required|date',
            'numero_contrato' => [
                'required',
                Rule::unique('ce_proconminutas','numero_contrato')->ignore($this->id)
            ],
            'pr_cdp_id' => 'nullable|exists:pr_cdps,id',
            'ce_tipocontrato_id' => 'exists:ce_tipocontratos,id',
            'plazo_meses' => 'required_without:plazo_dias',
            'plazo_dias' => 'required_without:plazo_meses',
            'valor' => 'required',
            'estado' => [
                'required',
                Rule::in($estados)
            ]
        ];

//        if ($this->estado === 'Confirmado') {
//            $rules['minuta'] = 'required';
//        }

        return $rules;
    }
}
