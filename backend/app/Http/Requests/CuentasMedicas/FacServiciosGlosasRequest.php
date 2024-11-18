<?php
namespace App\Http\Requests\CuentasMedicas;

use App\Models\CuentasMedicas\CmFacservicio;
use Illuminate\Foundation\Http\FormRequest;

class FacServiciosGlosasRequest extends FormRequest
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
        if ($this['avalado'] === 1) return [];

        $servicio = CmFacservicio::whereId($this['cm_facservicio_id'])->first();

        $valor = (($servicio->cantidad * $servicio->valor_unitario) - $servicio->copago);

        $valor_glosa = 0;
        if ($this['tipo'] === 'Porcentaje') {
            $porcentaje = $this['valor_glosa'];
            $valor_glosa = round(($valor * ($porcentaje / 100)),2);
        }
        $valor_total = ($valor_glosa !== 0 ? $valor_glosa : $valor);

        return [
            'cm_manglosa_id' => 'required|exists:cm_manglosas,id',
            'valor_glosa' => 'required|numeric|min:'. 0 .'|max:' . ($valor_total)
        ];
    }
}
