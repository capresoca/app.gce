<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 21/03/2019
 * Time: 2:56 PM
 */

namespace App\Http\Requests;


use App\Models\Aseguramiento\AsPagadore;
use Illuminate\Validation\Rule;

trait NovedadesRules
{
    protected function N01(){
        return [
            'v1' => 'required|exists:gn_tipdocidentidades,id',
            'v2' => [
                'required',
                'min:3',
                'max:16',
//                'exists:as_afiliados,identificacion'
                Rule::unique('as_afiliados','identificacion')->ignore($this->afiliado->id)
            ],
            'v3' => 'required|date',
            'v4' => 'required|exists:as_tipcambiodocs,id'
        ];
    }

    protected function N02(){
        return [
            'v1' => [
                'required',
                'max:20',
                'alpha_dash'
            ],
            'v2' => 'nullable|alpha_dash|max:30|string',
        ];

    }

    protected function N03(){
        return [
            'v1' => 'required|max:20|alpha_dash',
            'v2' => 'nullable|max:30|string',
        ];

    }

    protected function N04(){
        return [
            'v1' => 'required|exists:gn_municipios,id'
        ];

    }

    protected function N05(){
        if(!$this->afiliado->cabeza){
            return [
                'segundo_cotizante' => 'required'
            ];
        }

        return [];
    }

    protected function N06(){
        $rules = [];
        if($this->afiliado->as_tipafiliado_id !== 1){
            $rules['cotizante'] = 'required';
        }

        $rules['v1'] = 'required|exists:as_clasecotizantes,id';
        $rules['v3'] = 'required|exists:as_pagadores,id';
        $rules['v2'] = 'required|exists:nf_ciius,id';

        return $rules;
    }

    protected function N07(){

        $rules = [
            'v1' => 'required|exists:as_parentescos,id',
            'v2' => 'exists:as_condicion_discapacidades,id',
            'v3' => 'required|exists:as_afiliados,id'
        ];


        if(!$this->afiliado->cabfamilia_id === 1 && $this->afiliado->beneficiarios->count()){
            $rules['cotizante_solo'] = 'required';
        }

        return $rules;

    }


    protected function N08(){
        $rules = [
            'v1' => 'required|exists:as_clasecotizantes,id',
            'v3' => 'required|exists:as_pagadores,id',
            'v2' => 'required|exists:nf_ciius,id'
        ];

        if($this->afiliado->as_tipafiliado_id === 1){
            $rules['no_cotizante'] = 'required';
        }

        if(!$this->afiliado->cabfamilia_id){
            $rules['cotizante_principal'] = 'required';
        }


        return $rules;

    }


    protected function N09(){
        return [

        ];

    }


    protected function N10(){
        $rules = [
            'v1' => 'required|exists:as_clasecotizantes,id',
            'v2' => 'required|exists:nf_ciius,id',
            'v3' => 'required|exists:as_pagadores,id',
            'v4' => 'required|date',
        ];

        if($this->afiliado->as_tipafiliado_id !== 1){
            $rules['cotizante'] = 'required';
        }

        return $rules;
    }


    protected function N11(){
        $pagador = AsPagadore::find($this->v1);

        $rules = [
            'v1' => 'required|exists:as_pagadores,id',
            'v2' => 'required|exists:as_clasecotizantes,id',
        ];

        if($pagador && !$pagador->tercero){
            $rules['tercero'] = 'required';
        }


        if($this->afiliado->as_tipafiliado_id !== 1){
            $rules['cotizante'] = 'required';
        }

        return $rules;

    }

    protected function N12(){
        return [
            'v1'=> 'exists:as_condicion_discapacidades,id'
        ];

    }


    protected function N13(){
        $rules =  [
            'v1'=> 'required|exists:as_condterminaciones,id',
            'v2'=> 'required|date'
        ];


        if($this->afiliado->as_regimene_id !== 2){
            $rules['subsidiado'] = 'required';
        }

        return $rules;

    }


    protected function N14(){
        $rules = [
            'v2' => 'exists:as_condterminaciones,id'
        ];

        if($this->afiliado->as_regimene_id === 1){
            if($this->afiliado->estado === 3){
                $rules['v1'] = 'required|in:5,9,4';
            }else{
                $rules['v1'] = 'required|in:4';
            }
        }
        if($this->afiliado->as_regimene_id === 2) {

            $rules['v1'] = 'required|in:4';
        }


        if($this->afiliado->estado === 8){
            $rules['v1'] = 'required|in:4';
        }
        return $rules;


    }



    protected function N16(){
        return [];

    }


    protected function N17(){
        return [];
    }


    protected function N19(){
        return [];

    }

    protected function N20(){
        $rules = [];
        $rules['v1'] = 'required|in:1,2,3';
        $rules['v2'] = 'required|min:0|max:100';

        if($this->afiliado->as_regimene_id !== 2){
            $rules['subsidiado'] = 'required';
        }
        return $rules;
    }


    protected function N21(){
        $rules = [];

        $rules['v1'] = 'required|exists:as_pobespeciales,id';
        if($this->afiliado->as_regimene_id !== 2){
            $rules['subsidiado'] = 'required';
        }

        return $rules;

    }


    protected function N25(){
        return [
            'v1' => 'required|exists:rs_entidades,id'
        ];

    }


    protected function N31(){
        $rules = [];
        if($this->afiliado->as_regimene_id !== 2){
            $rules['subsidiado'] = 'required';
        }

        $rules['v1'] = 'exists:as_pobespeciales,id';
        $rules['v2'] = 'in:1,2,3';
        $rules['v3'] = 'min:0|max:100';

        return $rules;

    }


    protected function N32(){
        $rules = [];

        $rules['v1'] = 'exists:as_afiliados,id';
        $rules['v2'] = 'exists:as_parentescos,id';
        $rules['v3'] = 'exists:as_condicion_discapacidades,id';

        return $rules;

    }


    protected function N33(){
        return [
            'v1' => 'date|after:'. today()->subDays(375)->toDateString().'|before:'.today()->addDays(40)->toDateString()
        ];

    }


    protected function N35(){
        return [
            'v1' => 'date',
        ];

    }


    public function messages()
    {
        return [
            'segundo_cotizante.required' => 'El afiliado debe ser un segundo cotizante',
            'cotizante.required' => 'El afiliado debe ser cotizante',
            'cotizante_solo.required' => 'El cotizante no puede tener un grupo familiar activo',
            'cotizante_principal.required' => 'El afiliado ya es un cotizante principal',
            'subsidiado.required' => 'El afiliado debe ser del régimen subsidiado',
            'no_cotizante.required' => 'El afiliado ya es cotizante',
            'tercero.required' => 'El pagador no tiene datos de tercero, debe actualizarlo para crear esta novedad'
        ];
    }
}