<?php

namespace App\Http\Requests\Aseguramiento;

use App\Models\Aseguramiento\AsFormafiliacione;
use Illuminate\Foundation\Http\FormRequest;

class FormafiliacionRequest extends FormRequest
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
        if($this->recien_nacido){
            return [];
        }

        $rules = [
            'as_regimene_id' => 'required|exists:as_regimenes,id',
            'as_tipafiliado_id' => 'required|exists:as_tipafiliados,id',
            'as_afiliado_id' => 'required|exists:as_afiliados,id',
            'rs_ips_id' => 'required|exists:rs_entidades,id',
            'gn_municipio_id' => 'required|exists:gn_municipios,id',
            'estado' => 'required|in:Registrado,Radicado'
        ];

        if($this->estado === 'Anulado'){
            return [
                'detalle_anulacion' => 'required'
            ];
        }
        if($this->id){
            $formafiliacion = AsFormafiliacione::find($this->id);


            if($formafiliacion->estado === 'Registrado'){
                $rules_radicado = [
                    'estado' => 'in:Registrado,Radicado,Anulado'
                ];

                if($this->estado === 'Radicado'){
                    $rules_radicado['fecha_radicacion'] = 'required';
                }
                return $rules_radicado;
            }

            if($formafiliacion->estado === 'Radicado'){
                if(is_null($formafiliacion->as_archivoreporte_id)){
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

