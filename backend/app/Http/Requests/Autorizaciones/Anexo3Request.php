<?php

namespace App\Http\Requests\Autorizaciones;

use Illuminate\Foundation\Http\FormRequest;

class Anexo3Request extends FormRequest
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
        if ($this->ind !== '2') {
            $rules = [
                'fOrdMed'           => 'required',
                'codigoIps'         => 'required|exists:rs_entidades,cod_habilitacion',
                'orgAten'           => 'required|exists:au_origen_atencions,codigo',
                'serSol'            => 'required|exists:refsersol,codigo',
                'prior'             => 'required|numeric|max:2',
                'ubic'              => 'required|exists:refubic,codigo',
                //'jusCli'            => 'string|min:30|max:255',
                'dPrin'             => 'required|exists:rs_cie10s,codigo',
                'as_afiliado_id'    => 'required|exists:as_afiliados,id',
                'viaSol'            => 'required|exists:refviasol,codigo',
                'au_medico_id'      => 'required|exists:au_medicos_solicitantes,id',
                'oriAut'            => 'required|exists:reforigenautorizacion,codigo',
                'detalles'          => 'required'
            ];

            if ($this->ubic === '3') {
                $rules['cama'] = 'required';
                $rules['serv'] = 'required';
            }

            if ($this->oriAut === '3') {
                $rules['oj_tutela_id'] = 'required|exists:oj_tutelas,id';
            }
        } else {
            $rules = [
                'observacion' => 'required',
                'ind' => 'required|in:2'
            ];
        }

        return $rules;

    }
}
