<?php

namespace App\Http\Requests\CuentasMedicas;

use Illuminate\Foundation\Http\FormRequest;

class CensoRequest extends FormRequest
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
            'archivo'   => 'file|required',
            'fecha'     => 'date|required'
        ];
    }
}
