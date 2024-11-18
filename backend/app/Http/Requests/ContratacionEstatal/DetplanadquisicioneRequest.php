<?php

namespace App\Http\Requests\ContratacionEstatal;

use Illuminate\Foundation\Http\FormRequest;

class DetplanadquisicioneRequest extends FormRequest
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
            'ce_planadquisicione_id' => 'required|exists:ce_planadquisiciones,id',
            'ce_bienservicio_id' => 'required|exists:ce_bienservicios,id',
            'pg_uniapoyo_id' => 'required|exists:pg_uniapoyos,id',
            'ce_modcontratacione_id' => 'required|exists:ce_modcontrataciones,id',
            'fecha_proceso' => 'required|date',
            'fecha_ofertas' => 'required|date',
            'duracion' => 'required|max:11',
            'valor' => 'required',
            'rubros' => 'required'
        ];
    }
}
