<?php
/**
 * Created by PhpStorm.
 * User: Jorge Eduardo
 * Date: 14/03/2019
 * Time: 11:02 AM
 */

namespace App\Http\Repositories\CuentasMedicas;

use App\Http\Repositories\Repository;
use App\Models\CuentasMedicas\CmEstadosrad;
use App\Models\CuentasMedicas\CmRadcambio;
use App\Models\CuentasMedicas\CmRadicado;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RadicadosRepository implements Repository
{

    public function guardar($requestArray)
    {
        $cambioRad = CmRadcambio::create($requestArray);

        return $cambioRad;
    }

    public function validar($requestArray)
    {
        $rules = [
            'cm_radicado_id' => 'required',
            'cm_estadosrad_id' => 'required',
            'gs_usuario_id' => 'required',
            'aceptado' => 'required'
        ];
        $radicado = CmRadicado::with('estadolote.status_check','facturas')
            ->select('id','estado')
            ->where('id',$requestArray['cm_radicado_id'])->first();
        $nameStatus = CmEstadosrad::find($requestArray['cm_estadosrad_id']);
        $numFacts = count($radicado->facturas);
        $facturas = collect($radicado->facturas);

        switch ($nameStatus['estado']) {
            case 'Radicado':
                break;
            case 'Auditoria':
                break;
            case 'Contabilidad':
                if ($requestArray['aceptado'] === '3') {
                    $nFactsEstados = count($facturas->whereNotIn('estado',['Radicada','Asignada','Auditando']));
                    if ($numFacts > $nFactsEstados) {
                        $rules['no_enviarcontabilidad'] = 'required';
                    }
                }
                break;
            case 'Tesoreria':
                break;
            case 'Pagado':
                break;
        }

        if ($nameStatus['estado'] === 'Devuelta') {
            $rules['descripcion'] = 'required';
        }

        $messages = [
            'no_enviarcontabilidad.required' => 'No es posible enviar a contabilidad, aún se encuentran facturas sin Auditar'
            //'detalles.required' => 'Debe existir al menos un detalle.'
        ];

        $validator = Validator::make($requestArray, $rules, $messages);

        if($validator->fails()){
            throw new ValidationException($validator->errors());
        }
    }

}
