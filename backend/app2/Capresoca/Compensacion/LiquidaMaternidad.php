<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 23/01/2019
 * Time: 5:47 PM
 */

namespace App\Capresoca\Compensacion;


use App\Models\Aseguramiento\AsAfiliadoPagador;
use App\Models\AtencionUsuario\AuIncapacidade;
use App\Models\RedServicios\RsSalminimo;
use App\Traits\CarbonColombia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class LiquidaMaternidad
{
    protected $incapacidad;
    protected $relacionLaboral;
    protected $dias_cotizados;
    protected $dias_prematuro = 0;
    protected $dias_neto = 0;
    protected $dias_pagar = 0;



    public function __construct(AuIncapacidade $incapacidade)
    {
        try{

            $this->incapacidad = $incapacidade;
            $this->relacionLaboral = $incapacidade->relacion_laboral;
            $this->setDiasPrematuro();
            $this->liquidar();
        }catch (\Exception $e){
            throw $e;
        }

    }

    public function liquidar()
    {
        try{

            $this->aportes = $this->aportesGestacion($this->incapacidad);

            $dias_cotizados = $this->getDiasCotizados($this->aportes);

            if($this->incapacidad->tipo_incapacidad->prestacion->tipo != 'LICENCIA DE MATERNIDAD'){
                return;
            }
            $this->dias_cotizados = $dias_cotizados;
            $this->dias_pagar = $this->diasPagar($dias_cotizados);
            $this->incapacidad->dias_pagado = $this->dias_pagar;
            return array(
                'aportes'           => $this->aportes->toArray(),
                'tipo'              => $this->incapacidad->tipo_incapacidad->tipo,
                'dias_gestacion'    => $this->incapacidad->dias_gestacion,
                'dias_cotizados'    => $this->dias_cotizados,
                'dias_prematuro'    => $this->dias_prematuro,
                'dias_neto'         => $this->dias_neto,
                'dias_pagar'        => $this->dias_pagar
            );
        }catch (\Exception$e){
            throw $e;
        }

    }

    private function getDiasCotizados($aportes)
    {
        $dias_cotizados = $aportes->sum('dias');
        $dias_cotizados = $dias_cotizados > $this->incapacidad->dias_gestacion ? $this->incapacidad->dias_gestacion : $dias_cotizados;

        return $dias_cotizados;
    }

    /**
     * @param $dias_cotizados
     * @return float|int
     * @throws \Exception
     */
    private function diasPagar($dias_cotizados)
    {
        if($this->incapacidad->tipo_incapacidad->codigo === '2'/*Parto no viable*/)
        {
            return $this->incapacidad->tipo_incapacidad->dias;
        }


        if($this->incapacidad->tipo_incapacidad->codigo === '3'/*Paternidad*/)
        {
            return $this->diasPaternidad($dias_cotizados);
        }

        if($this->relacionLaboral->esIndependiente()){
            try{

                return $this->diasPagarIndependiente($dias_cotizados);
            }catch (\Exception $e){
                throw $e;
            }
        }

        return $this->diasProporcional($dias_cotizados);
    }

    private function diasPaternidad($dias_cotizados)
    {
        $fechaInicio = CarbonColombia::parse($this->incapacidad->fecha_inicio);
        $fechaFin = CarbonColombia::parse($this->incapacidad->fecha_inicio);

        $fechaFin->addBussinessDays($this->incapacidad->tipo_incapacidad->dias - 1);

        $dias_total = $fechaFin->diffInDays($fechaInicio,true);

        $this->incapacidad->fecha_fin = $fechaFin->toDateString();
        $this->incapacidad->dias_incapacidad_total = $dias_total;
        $this->incapacidad->save();


        if( $dias_cotizados < $this->incapacidad->dias_gestacion ){
            return 0;
        }


        return $dias_total;

    }

    /**
     * @param $dias_cotizados
     * @return float|int
     * @throws \Exception
     */
    private function diasPagarIndependiente($dias_cotizados){
        $year= substr($this->incapacidad->fecha_inicio,0,-6);
        $smlv = RsSalminimo::where('anio',$year)->first();

        if(!$smlv) throw new \Exception('No se ha configurado el salario minimo para es año '.$year);

        if($dias_cotizados >= 210 && $this->relacionLaboral->ibc == $smlv->valor){
            $this->dias_neto = $this->incapacidad->tipo_incapacidad->dias + $this->dias_prematuro;
        }

        return $this->diasProporcional($dias_cotizados);
    }

    private function setDiasPrematuro(){
        if(!$this->incapacidad->tipo_incapacidad->prematuro){
            $this->dias_prematuro = 0;
        }
        $this->dias_prematuro = $this->incapacidad->dias_prematuro;
    }


    private function diasProporcional($dias_cotizados)
    {

        $dias = $this->incapacidad->tipo_incapacidad->dias + $this->dias_prematuro;
        $this->dias_neto = $dias;
        if(!$this->incapacidad->dias_gestacion) return $dias;

        return round($dias_cotizados * $dias / $this->incapacidad->dias_gestacion);
    }


    private function aportesGestacion(AuIncapacidade $incapacidade)
    {
        $afiliado = $incapacidade->afiliado;

        $relaciones = $afiliado->relaciones_laborales->pluck('id');

        $parto = Carbon::parse($incapacidade->fecha_inicio);
        $inicio = Carbon::parse($incapacidade->fecha_inicio);
        $fecha_concepcion = $inicio->subDays($incapacidade->dias_gestacion);

        $aportes = AsAfiliadoPagador::JoinAportes()
            ->whereIn('as_afiliado_pagador.id',$relaciones)
            ->where('cp_liquidaciones.periodo','>=',$fecha_concepcion->format('Y-m'))
            ->where('cp_liquidaciones.periodo','<=',$parto->format('Y-m'))
            ->get();

        return $aportes;
    }


}
