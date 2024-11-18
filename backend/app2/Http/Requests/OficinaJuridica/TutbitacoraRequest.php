<?php

namespace App\Http\Requests\OficinaJuridica;

use Illuminate\Foundation\Http\FormRequest;

class TutbitacoraRequest extends FormRequest
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
            'tipo' => 'required|in:Manual,Automatica',
            'fecha' => 'required|date',
            'observacion' => 'required'
        ];
    }
}
