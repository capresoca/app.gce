<?php

namespace App\Http\Requests\Aseguramiento;

use App\Traits\EnumsTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ArchivoreporteRequest extends FormRequest
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
        $tipos = EnumsTrait::getEnumValues('as_archivoreportes', 'tipo');
        return [
            'tipo' => [
                'required',
                Rule::in($tipos)
            ]
        ];


    }
}
