<?php

namespace App\Http\Requests\TalentoHumano;

use App\Models\TalentoHumano\ThEncargo;
use App\Traits\EnumsTrait;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EncargoRequest extends FormRequest
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
        $tipos_encargos = implode(',', EnumsTrait::getEnumValues('th_encargos','tipo_encargo'));
        $fecha_inicio = $this->fecha_inicio;
        $fecha_fin = $this->fecha_fin;
        $rules = [
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
            'gs_usuario_id' => 'required|exists:gs_usuarios,id',
            'estado' =>  'required',
            'tipo_encargo' =>  'required|in:' . $tipos_encargos
        ];

        $encargo = $this->checkRange('fecha_inicio', $fecha_inicio, $fecha_fin, $this->gs_usuario_id, $this->tipo_encargo);

        if ($encargo && is_null($this->id)) {
            $rules['gs_usuario_id'] = Rule::unique('gs_usuarios')->ignore($this->id);
        }


        return $rules;
    }

    public function messages()
    {
        return [
            'gs_usuario_id.unique' => 'Ya se encuentra asignado el usuario a un encargo.'
        ];
    }

    private function checkRange ($fecha_column, $fecha_inicio, $fecha_fin, $gs_usuario_id, $tipo) {
        $val = ThEncargo::with('usuario')
            ->whereBetween($fecha_column,[$fecha_inicio,$fecha_fin])
            ->orWhereBetween('fecha_fin', [$fecha_inicio,$fecha_fin])
            ->where('gs_usuario_id', $gs_usuario_id)
            ->where('tipo_encargo',$tipo)->first();
//        dd('algo' . $val);
        return $val;
    }

}
