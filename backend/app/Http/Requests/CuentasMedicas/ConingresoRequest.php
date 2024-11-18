<?php

namespace App\Http\Requests\CuentasMedicas;

use Illuminate\Foundation\Http\FormRequest;

class ConingresoRequest extends FormRequest
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
        $rules = [
            'cm_concurrencia_id' => 'required | exists:cm_concurrencias,id',
            'fecha_ingreso' => 'required',
            'via_ingreso' => 'required',
            'dx_ingreso' => 'required'
        ];

        if($this->via_ingreso == 'Remitido'){
            $rules['rs_entidad_id'] = 'required | exists:rs_entidades,id';
        }

        return $rules;
    }
}
