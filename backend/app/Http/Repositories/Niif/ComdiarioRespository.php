<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 27/06/2018
 * Time: 3:45 PM
 */

namespace App\Http\Repositories\Niif;


use App\Http\Repositories\Repository;
use App\Models\Cartera\CrConfiguracione;
use App\Models\Cartera\CrCuentasxcobrar;
use App\Models\CuentasMedicas\CmRadicado;
use App\Models\General\GnEmpresa;
use App\Models\NfComdiariotran;
use App\Models\Niif\NfComdiario;
use App\Models\Niif\NfConrtf;
use App\Models\Niif\NfNiif;
use App\Models\Niif\NfRetencione;
use App\Models\Niif\NfTipcomdiario;
use App\Models\Pagos\PgConfiguracione;
use App\Models\Pagos\PgCxp;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ComdiarioRespository implements Repository
{

    public function guardar($requestArray)
    {
            if(!isset($requestArray['id'])){
                $requestArray['consecutivo'] = NfComdiario::max('consecutivo') + 1;
                $comdiario = NfComdiario::create($requestArray);
                $comdiario->detalles()->createMany($requestArray['detalles']);
            }else{
                $comdiario = NfComdiario::findOrFail($requestArray['id']);
                $comdiario->update($requestArray);
                $comdiario->detalles()->delete();
                $comdiario->detalles()->createMany($requestArray['detalles']);
            }
        $this->retencion($comdiario, $requestArray);
        return $comdiario;
    }

    public function validar($requestArray)
    {
        $rules = [
            'fecha' => 'required|date',
            'estado' => 'required',
            'detalles' => 'required'
        ];

        //Validaciones de campo en el arreglo de comdiario.detalles
        $totalCredito = 0;
        $totalDebito = 0;
        $auxiliares = NfNiif::where('nf_nivcuenta_id', '>', 4)->get()->pluck('id');
        foreach ($requestArray['detalles'] as $key => $detalle){
            $rules['detalles.' . $key . '.nf_niif_id'] = [
                'required',
                Rule::in($auxiliares)
            ];

            if($detalle['nf_niif_id']){
                $auxiliar = NfNiif::find($detalle['nf_niif_id']);
                if($auxiliar->maneja_tercero){
                    $rules['detalles.' . $key . '.gn_tercero_id'] = 'required|numeric';
                }
                if($auxiliar->maneja_ccosto){
                    $rules['detalles.' . $key . '.nf_cencosto_id'] = 'required|numeric';
                }
            }

            if($detalle['debito']==''){
                $rules['detalles.' . $key . '.credito'] = 'required|numeric';
            }
            if($detalle['credito']==''){
                $rules['detalles.' . $key . '.debito'] = 'required|numeric';
            }
            $totalCredito += $detalle['credito'];
            $totalDebito += $detalle['debito'];
        }

        if($totalCredito != $totalDebito){
            $rules['sumas_iguales'] = 'required';
        }

        $messages = [
            'detalles.1.nf_niif_id.required' => 'Se debe agregar al menos una cuenta para acreditar y una para debitar',
            'detalles.*.credito.required' => 'Se debe agregar al menos un valor para acreditar o uno para debitar',
            'detalles.*.debito.required' => 'Se debe agregar al menos un valor para acreditar o uno para debitar',
            'detalles.required' => 'Se debe agregar al menos una cuenta para acreditar y una para debitar',
            'sumas_iguales.required' => 'El total de débito debe ser igual al total de crédito'
        ];

        $validator = Validator::make($requestArray,$rules, $messages);

        if($validator->fails()){
            throw new ValidationException($validator->errors());
        }


    }

    public function createFromCxp (PgCxp $cxp) {
        $configuracion = PgConfiguracione::first();
        if (!isset($configuracion)) {
            throw new \Exception('No existe un tipo de comprobante registrado en la configuración del módulo de pagos.');
        }
//        $tipoComprobanteId = PgConfiguracione::first()->nf_tipcomcxp_id;
        $tipoComprobanteId = $configuracion->nf_tipcomcxp_id;
        $tipoComprobante = NfTipcomdiario::find($tipoComprobanteId);
        $comdiarioCxp = [
            'pg_cxp_id' => $cxp->id,
            'nf_tipcomdiario_id' => $tipoComprobanteId,
            'fecha' => $cxp->fecha_causacion,
            'detalle' => $tipoComprobante->nombre . ' | ' . $cxp->consecutivo,
            'estado' => $cxp->estado,
            'documento' => 'CXP No. ' . str_pad($cxp->consecutivo,6,"0",STR_PAD_LEFT),
            'detalles' => $this->detallesCxp($cxp)
        ];

        $comdiario = $this->guardar($comdiarioCxp);
        $this->registroModuloComdiario($cxp, $comdiario, 'cxp');

        return $comdiarioCxp;

    }

    public function detallesCxp ($cxp) {
        $detalles = [];
        $totalDebito = 0;
        $totalCredito = 0;
        foreach ($cxp->detalles as $detalleCXP) {
            $creditoCxp = 0;
            $debitoCxp = 0;
//            if ($detalleCXP->niif->clascuenta->naturaleza == 'Crédito') {
            if ($detalleCXP->naturaleza == 0) {
                $totalCredito +=$detalleCXP->valor;
                $creditoCxp = $detalleCXP->valor;
            }
//            if ($detalleCXP->niif->clascuenta->naturaleza == 'Débito') {
            if ($detalleCXP->naturaleza == 1) {
                $totalDebito +=$detalleCXP->valor;
                $debitoCxp = $detalleCXP->valor;
            }
//            dd($detalleCXP);
            array_push($detalles, [
                'retencion' => $detalleCXP->retencion,
                'nf_conrtf_id' => isset($detalleCXP->nf_conrtf_id) ? $detalleCXP->nf_conrtf_id : null,
                'nf_niif_id' => $detalleCXP->nf_niif_id,
                'gn_tercero_id' => $detalleCXP->gn_tercero_id,
                'nf_cencosto_id' => $detalleCXP->nf_cencosto_id,
                'credito' => $creditoCxp,
                'debito' => $debitoCxp
            ]);

        }
        $detalleCabecera = [
            'retencion' => $detalleCXP->retencion,
            'nf_conrtf_id' => isset($cxp->nf_conrtf_id) ? $cxp->nf_conrtf_id : null,
            'nf_niif_id' => $cxp->nf_niif_id,
            'gn_tercero_id' => $cxp->gn_tercero_id,
            'nf_cencosto_id' => $cxp->nf_cencosto_id,
            'credito' => $totalDebito - $totalCredito,
            'debito' => 0 // Verificar
        ];
        array_push($detalles, $detalleCabecera);

        return $detalles;
    }

    public function createFromCxc (CrCuentasxcobrar $cr_cuentasxcobrar) {
        $crTipoComprobanteId = CrConfiguracione::first()->nf_tipcomdiario_cxc_id;
        $tipoComprobante = NfTipcomdiario::find($crTipoComprobanteId);
        $comdiarioCxc = [
            'cr_cuentasxcobrar_id' => $cr_cuentasxcobrar->id,
            'nf_tipcomdiario_id' => $crTipoComprobanteId,
            'fecha' => $cr_cuentasxcobrar->fecha,
            'detalle' => $tipoComprobante->nombre . ' | ' . $cr_cuentasxcobrar->consecutivo,
            'estado' => $cr_cuentasxcobrar->estado,
            'detalles' => $this->detallesCxc($cr_cuentasxcobrar)
        ];

        $this->guardar($comdiarioCxc);

        return $comdiarioCxc;
    }

    public function detallesCxc ($cr_cuentasxcobrar) {
        $detalles = [];
        $totalDebito = 0;
        $totalCredito = 0;
        foreach ($cr_cuentasxcobrar->detalles as $detalleCXC) {
            $creditoCxp = 0;
            $debitoCxp = 0;
            if ($detalleCXC->niif->clascuenta->naturaleza === 'Crédito') {
                $totalCredito +=$detalleCXC->valor;
                $creditoCxp = $detalleCXC->valor;
            }
            if ($detalleCXC->niif->clascuenta->naturaleza === 'Débito') {
                $totalDebito +=$detalleCXC->valor;
                $debitoCxp = $detalleCXC->valor;
            }
            array_push($detalles, [
                'nf_niif_id' => $detalleCXC->nf_niif_id,
                'gn_tercero_id' => $detalleCXC->gn_tercero_id,
                'nf_cencosto_id' => $detalleCXC->nf_cencosto_id,
                'credito' => $creditoCxp,
                'debito' => $debitoCxp
            ]);

        }
        $detalleCabecera = [
            'nf_niif_id' => $cr_cuentasxcobrar->nf_niif_id,
            'gn_tercero_id' => $cr_cuentasxcobrar->gn_tercero_id,
            'nf_cencosto_id' => $cr_cuentasxcobrar->nf_cencosto_id,
            'credito' => $totalDebito - $totalCredito,
            'debito' => 0 // Verificar
        ];
        array_push($detalles, $detalleCabecera);

        return $detalles;
    }

    public function createComcuentaradicada (CmRadicado $cm_radicado) {
        $empresa = GnEmpresa::with(
            'cuentaDebitoprovisional.clascuenta',
            'cuentaCreditoprovisional.clascuenta',
            'cuentadebitoSincontrato.clascuenta',
            'cuentacreditoSincontrato.clascuenta',
            'comdiarioSincontrato',
            'comdiarioConcontrato')
            ->first();
        if ($cm_radicado->rs_contrato_id) {
            if (!isset($empresa['nf_niifdebitoprovisional_id']) || !isset($empresa['nf_niifcreditoprovisional_id']) || !isset($empresa['nf_comdiarioprovisional_id'])) {
                throw new \Exception('La configuración para el prestador con contrato en el módulo de empresa esta incompleta.');
            }
            $crTipoComprobanteId = $empresa['comdiarioConcontrato']['id'];
            $cuentaCredito = $empresa['cuentaCreditoprovisional'];
            $cuentaDebito = $empresa['cuentaDebitoprovisional'];
        } else {
            if (!isset($empresa['nf_niifdebitoprosincontrato_id']) || !isset($empresa['nf_niifcreditoprosincontrato_id']) || !isset($empresa['nf_comdiarioprovisionalsincontrato_id'])) {
                throw new \Exception('La configuración para el prestador sin contrato en el módulo de empresa esta incompleta.');
            }
            $crTipoComprobanteId = $empresa['comdiarioSincontrato']['id'];
            $cuentaCredito = $empresa['cuentacreditoSincontrato'];
            $cuentaDebito = $empresa['cuentadebitoSincontrato'];
        }
        $comdiarioRadicado = [
            'nf_tipcomdiario_id' => $crTipoComprobanteId,
            'fecha' => $cm_radicado->fecha,
            'detalle' => 'Radicado Cuenta: ' . $cm_radicado->consecutivo . '| Radicado RIPS: '. $cm_radicado->ripRadicado()->first()['cod_radicacion_ct'] . ' | ' . $cm_radicado->observaciones,
            'estado' => 'Confirmado',
            'detalles' => $this->detallesRadicado($cm_radicado, $empresa, $cuentaDebito, $cuentaCredito)

        ];
        $comdiario = $this->guardar($comdiarioRadicado);
        $this->registroModuloComdiario($cm_radicado, $comdiario, 'radicado');

        return $comdiarioRadicado;
    }

    public function detallesRadicado ($cm_radicado, $empresa, $cuentaDebito, $cuentaCredito) {
        $detalles = [];
        $debitoCxp = isset($cuentaDebito) ? $cm_radicado->valorTotalNeto : '';
        $creditoCxp  = isset($cuentaCredito) ? $cm_radicado->valorTotalNeto : '';
        if ($cuentaDebito['maneja_tercero'] !== 0) {
            $idTercero = $cm_radicado->gn_tercero_id;
        }
        if ($cuentaCredito['maneja_tercero'] !== 0) {
            $idTerceroCredito = $cm_radicado->gn_tercero_id;
        }
        array_push($detalles,
            [
                'nf_niif_id' => $cuentaDebito['id'],
                'gn_tercero_id' => $idTercero,
                'nf_cencosto_id' => null,
                'credito' => 0,
                'debito' => $debitoCxp
            ],
            [
                'nf_niif_id' => $cuentaCredito['id'],
                'gn_tercero_id' => $idTerceroCredito,
                'nf_cencosto_id' => null,
                'credito' => $creditoCxp,
                'debito' => 0
            ]
        );
        return $detalles;
    }

    public function retencion ($comdiario, $value) {
        foreach ($value['detalles'] as $detalle) {
            $auxiliar = isset($detalle['niif']) ? $detalle['niif'] : NfNiif::find($detalle['nf_niif_id']);
            $concepto = isset($detalle['nf_conrtf_id']) ? NfConrtf::where('id', $detalle['nf_conrtf_id'])->first() : null;
            //                dd($concepto);
//                isset($auxiliar['tipo']) &&
            if ($auxiliar['tipo'] === 'Retencion') {
                $retencion = new NfRetencione();
                $retencion->nf_comdiario_id = isset($value['id']) ? $value['id'] : $comdiario->id;
                $retencion->pg_cxp_id = isset($value['pg_cxp_id']) ? $value['pg_cxp_id'] : null;
                $retencion->cr_cuentasxcobrar_id = isset($value['cr_cuentasxcobrar_id']) ? $value['cr_cuentasxcobrar_id'] : null;;
                $retencion->porcentaje = $concepto->porcentaje;
                $retencion->valor_base = $detalle['retencion'];
                $vRtf = $detalle['retencion'] * ($concepto->porcentaje / 100);
                $retencion->valor_retencion = $vRtf;
                $retencion->save();
            }
        }
    }

    /**
     * @param $item
     * @param $comdiario
     * @param $text
     */
    private function registroModuloComdiario($item, $comdiario,string $text): void
    {
        $trans = new NfComdiariotran();
        $trans->nf_comdiario_id  = $comdiario['id'];
        $trans->cm_radicado_id   = ($text === 'radicado') ? $item['id'] : null;
        $trans->pg_cxp_id        = ($text === 'cxp') ? $item['id'] : null;
        $trans->pr_obligacion_id = ($text === 'obligacion') ? $item['id'] : null;
        $trans->save();
    }
}
