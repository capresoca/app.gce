<?php

namespace App\Http\Requests\RedServicios;

use App\Models\RedServicios\RsMaestroipsgrup;
use Illuminate\Foundation\Http\FormRequest;

class RsAsignacionRequest extends FormRequest
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
        $descrip = '';
        if (isset($this->id)) {
            $descrip = RsMaestroipsgrup::select('id','descrip')->whereId($this->id)->first()->descrip;
        }

        $rules = [
            'municipio_id' => 'required',
            'departamento_id' => 'required',
            'descrip' => 'required' . ($this['descrip'] !== $descrip ? '|unique:rs_maestroipsgrups,descrip' :'')
        ];

        return $rules;
    }
}
