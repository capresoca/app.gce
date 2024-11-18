<?php

namespace App\Http\Requests\CuentasMedicas;

use App\Models\Riips\RsRipsRadicado;
use Illuminate\Foundation\Http\FormRequest;

class RadicadoRequest extends FormRequest
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
        if ($this->id) {
            return [];
        }

        $modalidad = RsRipsRadicado::select('id', 'tipo_facturacion')->where('id', $this['rs_rip_radicado_id'])->first()->tipo_facturacion;

        return [
            'periodo_inicio' => 'required',
            'periodo_fin' => 'required',
            'periodo_fin' => 'required',
            'radicado_entidad' => 'required|max:15',
            'fecha_radicado' => 'required',
            'rs_entidad_id' => 'required|exists:rs_entidades,id',
            'observaciones' => 'required',
            'rs_contrato_id' => (($modalidad === 'Capita')) ? 'required' : 'nullable',
        ];
        //Rule::unique('cm_radicados','radicado_entidad')->ignore($this->id)

    }

    public function messages()
    {
        return [
            'rs_contrato_id.required' => 'El campo contrato es obligatorio.',
            'rs_entidad_id.required' => 'El campo entidad es obligatio.'
        ];
    }
}
