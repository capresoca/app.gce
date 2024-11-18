<?php

namespace App\Http\Requests\CuentasMedicas;

use App\Models\CuentasMedicas\CmGlosa;
use Illuminate\Foundation\Http\FormRequest;

class FacturasRequest extends FormRequest
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

        $exist = CmGlosa::select('id','cm_factura_id','cm_manglosa_id')->where('cm_factura_id','=', $this->cm_factura_id)
            ->where('cm_manglosa_id','=',$this->cm_manglosa_id)->first();

        if($exist){$rules['exists_facglosas'] = 'required';}

        $rules = [
            'cm_manglosa_id' => 'required',
            'observacion' => 'required'
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'exists_facglosas.required' => "Ya existe la glosa en las Glosas de Factura",
            'observacion.required' => "El campo observaciones es obligatorio."
        ];
    }
}
