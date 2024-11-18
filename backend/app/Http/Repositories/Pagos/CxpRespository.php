<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 27/06/2018
 * Time: 3:45 PM
 */

namespace App\Http\Repositories\Pagos;


use App\Http\Repositories\Repository;
use App\Models\Pagos\PgConpago;
use App\Models\Pagos\PgCxp;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CxpRespository implements Repository
{

    public function guardar($requestArray)
    {
        if(!isset($requestArray['id'])){
            $requestArray['consecutivo'] = PgCxp::pluck('consecutivo')->last() + 1;
            $pg_cxp = PgCxp::create($requestArray);
            $pg_cxp->detalles()->createMany($requestArray['detalles']);
        }else{
            $pg_cxp = PgCxp::findOrFail($requestArray['id']);
            $pg_cxp->update($requestArray);
            $pg_cxp->detalles()->delete();
            $pg_cxp->detalles()->createMany($requestArray['detalles']);
        }
        return $pg_cxp;
    }

    public function validar($requestArray)
    {
        $rules = [
            'fecha_causacion' => 'required|date',
            'estado' => 'required',
            'detalles' => 'required'
        ];

        //Validaciones de campo en el arreglo de comdiario.detalles
//        $totalCredito = 0;
//        $totalDebito = 0;
//        $auxiliares = NfNiif::where('nf_nivcuenta_id', '>', 4)->get()->pluck('id');
        if ($requestArray['detalles']) {
            foreach ($requestArray['detalles'] as $key => $detalle){
                if ($requestArray['modulo'] !== 'Saldo Inicial') {
                    if ($detalle['pg_cxp_id'] && $detalle['pg_conpago_id'] && $detalle['pg_uniapoyo_id']) {
                        $rules['detalles.' . $key . '.pg_cxp_id'] = 'required | numeric';
                        $rules['detalles.' . $key . '.pg_conpago_id'] = 'required | numeric';
                        $rules['detalles.' . $key . '.pg_uniapoyo_id'] = 'required | numeric';
                    }

                    if($detalle['pg_conpago_id']){
                        $pgconpago = PgConpago::find($detalle['pg_conpago_id']);
                        $auxiliar = $pgconpago->niif;
                        if($auxiliar->maneja_tercero){
                            $rules['detalles.' . $key . '.gn_tercero_id'] = 'required | numeric';
                        }
                        if($auxiliar->maneja_ccosto){
                            $rules['detalles.' . $key . '.nf_cencosto_id'] = 'required | numeric';
                        }
                    }
                }

                if ($requestArray['modulo'] === 'Saldo Inicial') {
                    $rules['detalles.' . $key . '.gn_tercero_id'] = 'required | numeric';
                    $rules['detalles.' . $key . '.nf_niif_id'] = 'required | numeric';
                }

                if ($detalle['valor']=='') {
                    $rules['detalles.' . $key . '.valor'] = 'required | numeric';
                }
            }
            return $rules;
        }

        if ($requestArray['modulo'] !== 'Saldo Inicial') {
            $messages = [
                'detalles.1.pg_cxp_id.required' => 'Se debe agregar una cuenta por pagar.',
                'detalles.1.pg_conpago_id.required' => 'Se debe agregar un concepto de pago.',
                'detalles.1.pg_uniapoyo_id.required' => 'Se debe agregar la unidad de apoyo',
                'detalles.*.valor.required' => 'Se debe agregar el valor del detalle de la cuenta.'
            ];
        } else {
            $messages = [
                'detalles.1.pg_cxp_id.required' => 'Se debe agregar una cuenta por pagar.',
                'detalles.*.valor.required' => 'Se debe agregar el valor del detalle de la cuenta.'
            ];
        }

        $validator = Validator::make($requestArray,$rules, $messages);

        if($validator->fails()){
            throw new ValidationException($validator->errors());
        }


    }
}