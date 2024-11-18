<?php

namespace App\Http\Requests\Aseguramiento;

use App\Models\Niif\GnTercero;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FormpagadoreRequest extends FormRequest
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
            'gn_tercero_id' => 'required|exists:gn_terceros,id',
            'as_tipaportante_id' => 'required|exists:as_tipaportantes,id',
            'sector_aportante' => 'required|in:Público,Privado,Mixto'
        ];

        $tipoIdentificacionTercero = GnTercero::find($this->gn_tercero_id);
        if ($tipoIdentificacionTercero->gn_tipdocidentidad_id === 12) {
            return [
                'digito_verificacion' => 'required|max:10'
            ];
        }

        if ($this->estado === 'Anulado') {
            return [
              'detalle_anulacion' => 'required'
            ];
        }

        if ($this->estado === 'Radicado') {
            return [
              'fecha_radicacion' => 'required|date'
            ];
        }

        return $rules;
    }
}
