<?php

namespace App\Http\Requests\Aseguramiento;

use App\Http\Requests\NovedadesRules;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsTipnovedade;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NovtramiteRequest extends FormRequest
{
    use NovedadesRules;

    protected $afiliado;
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
        if($this->estado == 'Anulado'){
            return [
                'detalle_anulacion' => 'required'
            ];
        }

        $rules = [
            'as_afiliado_id' => 'required|exists:as_afiliados,id',
            'as_tipnovedade_id' => 'required|exists:as_tipnovedades,id',
            'as_fecha_ini_novedad' => 'date|after:'. today()->subDays(375)->toDateString().'|before:'.today()->addDays(40)->toDateString()
        ];

        $novedad = AsTipnovedade::find($this->as_tipnovedade_id)->codigo;
        $this->afiliado = AsAfiliado::find($this->as_afiliado_id);
        if($this->afiliado){
            $novedad_rules = $this->$novedad();
            $rules = array_merge($rules, $novedad_rules);
        }
        return $rules;

    }


}
