<?php

namespace App\Http\Requests\Pagos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PgProveedoreRequest extends FormRequest
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
            'gn_tercero_id' => 'required|exists:gn_terceros,id',
            'nf_niif_id' => 'required|exists:nf_niifs,id',
            'tipo_proveedor' => 'required',
            'plazo' => 'required|max:11'
        ];
    }
}
