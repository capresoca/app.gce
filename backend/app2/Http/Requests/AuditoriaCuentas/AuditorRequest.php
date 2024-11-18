<?php

namespace App\Http\Requests\AuditoriaCuentas;

use App\Models\AuditorCuentas\AcAuditore;
use App\Traits\EnumsTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AuditorRequest extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		$rules = [
            'tipo' => 'required|in:' . implode(',', EnumsTrait::getEnumValues('ac_auditores','tipo')),
			'gs_usuario_id' => 'required',
		];

        $exist = AcAuditore::where(
            [
                'tipo'    => $this->tipo,
                'tecnico' => $this->tecnico,
                'auditor_concurrente' => $this->auditor_concurrente
            ]
        )->where('gs_usuario_id','=',$this->gs_usuario_id)->first();

        if($exist){
            $rules['auditor_existente'] = 'required';
        }

        return $rules;
	}

    public function messages()
    {
        return [
            'auditor_existente.required' => 'El auditor ya existe en la base de datos'
        ];
    }
}