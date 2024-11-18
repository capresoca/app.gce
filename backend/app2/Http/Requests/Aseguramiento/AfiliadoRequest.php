<?php

namespace App\Http\Requests\Aseguramiento;

use App\Models\General\GnTipdocidentidade;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AfiliadoRequest extends FormRequest
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
        $rules =  [
            'gn_tipdocidentidad_id' => 'required|exists:gn_tipdocidentidades,id',
            'identificacion' => [
                'required',
                Rule::unique('as_afiliados','identificacion')->ignore($this->id)
            ],
            'nombre1' => 'required|string|max:45',
            'apellido1' => 'required|string|max:45',
            'direccion' => 'required|string|max:200',
            'fecha_expedicion' => 'required|date',
            'correo_electronico' => 'email|nullable',
            'ciudadania' => 'in:Nacional,Extranjero',
            'gn_municipio_id' => 'required|exists:gn_municipios,id',
            'as_parentesco_id' => 'nullable|exists:as_parentescos,id',
            'as_regimene_id' => 'nullable|exists:as_regimenes,id',
            'as_etnia_id' => 'nullable|exists:as_etnias,id',
            'cabfamilia_id' => 'nullable|exists:as_afiliados,id',
            'as_pobespeciale_id' => 'nullable|exists:as_pobespeciales,id',
            'as_clasecotizante_id' => 'nullable|exists:as_clasecotizantes,id',
            'as_condicion_discapacidades_id' => 'nullable|exists:as_condicion_discapacidades,id',
            'as_arl_id' => 'nullable|exists:as_arls,id',
            'as_afp_id' => 'nullable|exists:as_afps,id',
            'rs_entidade_id' => 'nullable|exists:rs_entidades,id',
            'estado' => 'exists:as_estadoafiliados,id',
            'as_ccf_id' => 'nullable|exists:as_ccfs,id',
            'gn_zona_id' => 'exists:gn_zonas,id',
            'gn_vereda_id' => 'nullable|exists:gn_veredas,id',
            'fecha_nacimiento' => 'required|date',
            'gn_barrio_id' => 'nullable|exists:gn_barrios,id',
            'zona_dnp' => 'nullable|exists:gn_zonas,id'
        ];

        $tipo_doc = GnTipdocidentidade::find($this->gn_tipdocidentidad_id);

        if($tipo_doc && !$this->id){
            $fecha_nacimiento_min = today()->subMonths($tipo_doc->edad_minima);

            $fecha_nacimiento_max = today()->subMonths($tipo_doc->edad_maxima)->subDay($tipo_doc->plazo_cambio);
            $rules['fecha_nacimiento'] = 'required|date|before:'.$fecha_nacimiento_min.'|after_or_equal:'.$fecha_nacimiento_max;
        }

        if($this->as_pobespeciale_id === 4 && $this->as_regimene_id === 2 && !$this->id){
            $rules['puntaje_sisben'] = 'required|max:100';
            $rules['zona_dnp'] = 'required|exists:gn_zonas,id';
        }


        if($this->gn_zona_id === 2){
            $rules['gn_vereda_id'] = 'required';
        } elseif ($this->gn_zona_id === 1) {
            $rules['gn_barrio_id'] = 'required';
        }

        return $rules;
    }
}


