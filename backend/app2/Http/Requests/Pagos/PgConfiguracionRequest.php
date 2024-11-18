<?php

namespace App\Http\Requests\Pagos;

use Illuminate\Foundation\Http\FormRequest;

class PgConfiguracionRequest extends FormRequest
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
            'nf_niifaprovechamiento_id' => 'required|exists:nf_niifs,id',
            'nf_niifdescuento_id' => 'required|exists:nf_niifs,id',
            'nf_tipcomcxp_id' => 'required|exists:nf_tipcomdiarios,id',
            'nf_tipcomnotadebito_id'  => 'required|exists:nf_tipcomdiarios,id',
            'nf_tipcomtraslado_id'  => 'required|exists:nf_tipcomdiarios,id',
            'nf_tipcomnotacredito_id'  => 'required|exists:nf_tipcomdiarios,id'
        ];
    }
}


