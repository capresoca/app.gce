<?php

namespace App\Http\Requests\ContratacionEstatal;

use Illuminate\Foundation\Http\FormRequest;

class ProcontractualeRequest extends FormRequest
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
            'objeto'            => 'required',
            'estado'            => 'required|in:Registrado,EstudioPrevio,Convocado,Adjudicado,Descartado,TerminadoAnormalmente,Celebrado,Liquidado,Terminado',
            'pr_dependencia_id' => 'required|exists:pr_dependencias,id',
            'servicios_salud'   => 'required',
        ];
    }
}
