<?php

namespace App\Http\Requests\OficinaJuridica;

use Illuminate\Foundation\Http\FormRequest;

class TutimpugnacionRequest extends FormRequest
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
            'oj_tutela_id' => 'required|exists:oj_tutelas,id',
            'desc_impugnacion' => 'required',
            'oj_tutfallo_id' => 'required|exists:oj_tutfallos,id',
            'fecha' => 'required|date'
        ];
    }
}
