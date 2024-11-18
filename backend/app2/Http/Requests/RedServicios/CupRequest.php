<?php

namespace App\Http\Requests\RedServicios;

use App\Traits\EnumsTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CupRequest extends FormRequest
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
        //pendiente: exists:cm_manisss,id
        return [
            'rs_cupscategoria_id' => 'nullable|exists:rs_cupscategorias,id',
            'cm_maniss_id' => 'nullable',
            'cm_mansoat_id' => 'nullable|exists:cm_mansoats,id',
            'codigo' => [
                'required',
                Rule::unique('rs_cupss')->ignore($this->id)
            ],
            'descripcion' => 'nullable|max:500',
            'genero' => 'nullable:in'.implode(',',EnumsTrait::getEnumValues('rs_cupss','genero')),
            'ambito' => 'nullable:in'.implode(',',EnumsTrait::getEnumValues('rs_cupss', 'ambito')),
            'estancia' => 'nullable:in'.implode(',', EnumsTrait::getEnumValues('rs_cupss', 'estancia')),
            'cobertura' => 'nullable:in'.implode(',', EnumsTrait::getEnumValues('rs_cupss', 'cobertura')),
            'duplicado' => 'nullable:in'.implode(',', EnumsTrait::getEnumValues('rs_cupss', 'duplicado')),
            'vida' => 'nullable:in'.implode(',', EnumsTrait::getEnumValues('rs_cupss', 'vida')),
            'frecuencia_uso' => 'nullable:in'.implode(',', EnumsTrait::getEnumValues('rs_cupss','frecuencia_uso')),
            'nivel_autorizacion' => 'nullable:in'.implode(',', EnumsTrait::getEnumValues('rs_cupss','nivel_autorizacion')),
            'pos' => 'nullable:in'.implode(',', EnumsTrait::getEnumValues('rs_cupss','pos'))
        ];
    }
}
