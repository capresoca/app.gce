<?php

namespace App\Http\Requests\ContratacionEstatal;

use Illuminate\Foundation\Http\FormRequest;

class ContratistaRequest extends FormRequest
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
            'ccomercio' => 'required|max:45',
            'fecha_ccomercio' => 'required|date',
            'rep_legal' => 'required|max:500',
            'gn_tercero_id' => 'required|exists:gn_terceros,id',
            'gn_munccomercio_id' => 'required|exists:gn_municipios,id',
            'ce_natjuridica_id' => 'required|exists:ce_natjuridicas,id'
        ];
    }
}
