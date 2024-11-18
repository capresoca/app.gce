<?php

namespace App\Http\Requests\Aseguramiento;

use App\Models\Aseguramiento\AsFormtrasmov;
use Illuminate\Foundation\Http\FormRequest;

class FormtrasmovRequest extends FormRequest
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
            'tipo' => 'required|in:Movilidad,Traslado',
            'as_afiliado_id' => 'required|exists:as_afiliados,id',
            'as_eps_id' => 'required|exists:as_epss,id',
            'gn_tipdocidentidad_id' => 'required|exists:gn_tipdocidentidades,id',
            'identificacion' => 'required',
            'nombre1' => 'required',
            'apellido1' => 'required',
            'fecha_nacimiento' => 'required',
            'estado' => 'required|in:Registrado,Radicado'
//            'as_padre_id' => 'required|exists:as_afiliados,id',
//            'as_parentesco_id' => 'required|exists:as_parentescos,id',
//             'as_pagadore_id' => 'required|exists:as_pagadores,id',
        ];


        if ($this->estado === 'Anulado') {
            return [
                'motivo_anula' => 'required'
            ];
        }
        if ($this->id) {

            $formtrasmov = AsFormtrasmov::find($this->id);
            if ($formtrasmov->estado === 'Registrado') {
                $rules = [
                    'estado' => 'in:Registrado,Radicado,Anulado'
                ];

                if ($this->estado === 'Radicado') {
                    $rules['fecha_radicacion'] = 'required';
                }
                return $rules;
            }
            if($formtrasmov->estado === 'Radicado'){
                if($formtrasmov->as_archivoreporte_id){
                    return [
                        'estado' => 'in:Radicado',
                    ]
                        ;
                }else{
                    return [
                        'estado' => 'required|in:Anulado',
                        'fecha_anulacion' => 'required|date'
                    ];
                }
            }
        }
        return $rules;
    }
}
