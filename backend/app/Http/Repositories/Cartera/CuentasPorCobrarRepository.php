<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 30/05/2018
 * Time: 8:45 AM
 */


namespace App\Http\Repositories\Cartera;


use App\Models\General\GnTipdocidentidade;
use App\Http\Repositories\Repository;
use App\Models\Niif\NfNiif;
use App\Traits\EnumsTrait;
use App\Traits\ToolsTrait;
use App\Models\Cartera\CrCuentasxcobrar;
use App\Models\Cartera\CrConceptoCuentasxcobrar;
use http\Env\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CuentasPorCobrarRepository implements Repository
{
    public function guardar($cuentaXCobrar)
    {
        if (!isset($cuentaXCobrar['id'])) {
            $cr_cuentaxcobrar = CrCuentasxcobrar::create($cuentaXCobrar);
            $cr_cuentaxcobrar->detalles()->createMany($cuentaXCobrar['detalles']);
        } else {
            $cr_cuentaxcobrar = CrCuentasxcobrar::findOrFail($cuentaXCobrar['id']);
            $cr_cuentaxcobrar->update($cuentaXCobrar);
            $cr_cuentaxcobrar->detalles()->delete();
            $cr_cuentaxcobrar->detalles()->createMany($cuentaXCobrar['detalles']);
        }
        return $cr_cuentaxcobrar;
    }
    public function validar($cuentaXCobrar)
    {
        $rules =  [
            'cr_cliente_id' => 'required|exists:cr_clientes,id',
            'nf_niif_id' => 'required|exists:nf_niifs,id',
            'numero_factura' => 'required|max:50',
            'palzo' => 'required',
            'fecha' => 'required|date',
            'fecha_factura' => 'required|date',
            'fecha_vencimiento' => 'required|date',
            'debitos' => 'required',
            'creditos' => 'required',
            'valor' => 'required',
            'observaciones' => 'required',
            'estado' => 'required|in:Registrado,Confirmado,Anulado',
            'detalles' => 'required'
        ];

        if ($rules['estado'] === 'Anulado') {
            $rules['detalle_anulacion'] = 'required';
        }

        if ($cuentaXCobrar['detalles']) {
            foreach ($cuentaXCobrar['detalles'] as $key => $detalle) {
                if ($detalle['cr_cuentaxcobrar_id'] && $detalle['cr_concepto_id']) {
                    $rules['detalles.' . $key . '.cr_cuentaxcobrar_id'] = 'required|numeric';
                    $rules['detalles.' . $key . '.cr_concepto_id'] = 'required|numeric|exists:cr_conceptos,id';
                }

                if ($detalle['nf_niif_id']) {
                    $nf_niif = NfNiif::find($detalle['nf_niif_id']);
                    $auxiliar = $nf_niif;
                    if ($auxiliar->maneja_tercero) {
                        $rules['detalles.' . $key . '.gn_tercero_id'] = 'required|numeric|exists:gn_terceros,id';
                    }
                    if ($auxiliar->maneja_ccosto) {
                        $rules['detalles.' . $key . '.nf_cencosto_id'] = 'required|numeric|exists:nf_cencostos,id';
                    }
                    if ($auxiliar->tipo === 'Retencion') {
                        $rules['detalles.' . $key . '.nf_conrtf_id'] = 'required|exists:nf_conrtfs,id';
                    }
                }

                if ($detalle['valor'] === '') {
                    $rules['detalles.' . $key . '.valor'] = 'required';
                }
            }
            return $rules;
        }

        $messages = [
            'detalles.1.cr_cuentaxcobrar_id.required' => 'Se debe agregar una cuenta por cobrar.',
            'detalles.1.cr_concepto_id.required' => 'Se debe agregar un concepto de cobro.',
            'detalles.1.nf_niif_id.required' => 'Se debe agregar una cuenta para el concepto.',
            'detalles.1.gn_tercero_id.required' => 'La cuenta maneja tercero, se debe agregar un tercero.',
            'detalles.1.nf_cencosto_id.required' => 'La cuenta maneja centro de costo, se debe agregar un centro de costo.',
            'detalles.1.nf_conrtf_id.required' => 'La cuenta es de tipo (Retención), se debe agregar un concepto de retención.',
            'detalles.*.valor.required' => 'Se debe agregar el valor base del detalle de la CXC.'
        ];


        $validator = Validator::make($cuentaXCobrar,$rules,$messages);

        if($validator->fails()){
            throw new ValidationException($validator->errors());
        }
    }
}