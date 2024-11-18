<?php

namespace App\Http\Requests\AtencionUsuario;

use Illuminate\Foundation\Http\FormRequest;

class PqrsdRequest extends FormRequest
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
            'tipo_usuario' => 'required|in:Afiliado,Otro',
            'as_afiliado_id' => 'nullable|exists:as_afiliados,id',
            'au_tipopqrsd_id' => 'required|exists:au_tipopqrsds,id',
            'funcionario_id' => 'exists:gs_usuarios,id',
            'nombres' => 'required|max:100',
            'apellidos' => 'max:100',
            'identificacion' => 'required',
            'gn_tipdocidentidad_id' => 'required|exists:gn_tipdocidentidades,id',
            'telefono' => 'required|max:20',
            'gn_municipio_id' => 'required|exists:gn_municipios,id',
            'email' => ! $this->email || $this->email === 'null' ? '' : 'email',
            'medio_recepcion' => 'required|in:Personalizado,Escrito,Web,Correo,Telefonico,Chat',
            'direccion' => 'required',
            'au_motivo_id' => 'nullable|exists:au_motivos,id',
            'plazo' => 'required|numeric',
            'rs_entidad_id' => 'required|exists:rs_entidades,id',
            'actores'   => 'required|in:Afiliado contra Capresoca,Afiliado contra IPS,Capresoca contra afiliado,Capresoca contra IPS,IPS contra afiliado'
        ];
    }
}
