<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 27/12/2018
 * Time: 8:37 AM
 */

namespace App\Capresoca\Compensacion;


use App\Compensacion\CpLiquidacione;
use App\Models\Aseguramiento\AsAfiliadoPagador;
use App\Models\AtencionUsuario\AuIncapacidade;
use App\Models\Compensacion\CpFechaslimite;
use App\Models\RedServicios\RsSalminimo;
use App\Traits\CarbonColombia;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class EstadoRelacionLaboral
{
    protected $relacionLaboral;
    protected $aportante;
    protected $afiliado;


    public function __construct(AuIncapacidade $incapacidade)
    {
        $this->incapacidad = $incapacidade;
        $this->relacionLaboral = $incapacidade->relacion_laboral;
        $this->aportante = $incapacidade->relacion_laboral->pagador;
        $this->afiliado = $incapacidade->relacion_laboral->afiliado;

    }


    /**
     * @param String $periodo
     * @return bool
     */
    public function periodoEnMora(String $periodo=null)
    {
        if(!$periodo)
            return $this->periodoActualMora();

        return $this->periodosHistoricoMora()->where('periodo',$periodo)->count() > 0;
    }


    /**
     * @return Collection
     */
    public function periodosHistoricoMora() : Collection
    {
        $periodo_actual = $this->getUltimoPeriodoVencido();

        return CpLiquidacione::where('relacion_laboral_id', $this->relacionLaboral->id)
                ->where('periodo', '<=', $periodo_actual)
                ->where(function ($query) {
                    $query->doesntHave('aportes')
                    ->orWhere(function ($query) {
                        $query->where('dias_pagados', '<', 30)
                            ->where('retiro', 0)
                            ->where('ingreso',0);
                    });
                })->get();
    }

    public function periodoActualMora()
    {
        return $this->periodosHistoricoMora()->where('periodo',$this->getUltimoPeriodoVencido())->count() > 0;
    }


    public function fechaLimitePeriodoActual()
    {
        $primer_dia_mes = CarbonColombia::today()->firstOfMonth();

        if(!$primer_dia_mes->isBussinessDay()){
            $primer_dia_mes->addBussinessDays($this->getDiaHabilPago());
            return $primer_dia_mes;
        }

        $primer_dia_mes->addBussinessDays($this->getDiaHabilPago() - 1);
        return $primer_dia_mes;

    }

    public function getDiaHabilPago()
    {
        $ultDigitos = $this->getDosUltimosDigitosIdentificacion();

        return CpFechaslimite::select('dia_habil')->where([
                    ['desde','<=', (int)$ultDigitos],
                    ['hasta','>=',(int)$ultDigitos]
            ])->first()->dia_habil;
    }

    public function getDosUltimosDigitosIdentificacion()
    {
        if($this->relacionLaboral->esIndependiente()){
            return substr($this->afiliado->identificacion,-2);
        }

        return substr($this->aportante->identificacion,-2);
    }



    private function getUltimoPeriodoVencido()
    {
        $ultimo_periodo = today()->subMonths(2)->firstOfMonth();

        if(today()->greaterThan($this->fechaLimitePeriodoActual())){
            $ultimo_periodo->addMonth();
        }

        return $ultimo_periodo->format('Y-m');
    }

    public function getPromedioIbcUltimoAnio($periodo = null)
    {
        try{

            if(!$periodo)
                $periodo = $this->getUltimoPeriodoVencido();

            $ultimoPeriodoMenosUnAnio = Carbon::createFromFormat('Y-m',$periodo)
                ->firstOfYear()->format('Y-m');

            return $this->relacionLaboral->liquidaciones()
                ->where('periodo','>=', $ultimoPeriodoMenosUnAnio)
                ->where('periodo','<=',$periodo)
                ->avg('ibc');

        }catch (\Exception $exception){
            throw $exception;
        }
    }


    /**
     * @return array
     * @throws \Exception
     */
    public function liquidacion()
    {
        try{

            $periodo_incapacidad = substr($this->incapacidad->fecha_inicio,0,-3);

            $liquidacion = [
                'periodo_actual_mora'       => $this->periodoActualMora(),
                'periodo_consultado_mora'   => $this->periodoEnMora($periodo_incapacidad),
                'promedio_ibc'              => $this->getPromedioIbcUltimoAnio($periodo_incapacidad),
                'independiente'             => $this->relacionLaboral->esIndependiente(),
                'smlv'                      => RsSalminimo::where('anio',substr($periodo_incapacidad,0,-3))->first(),
            ];

            if($this->incapacidad)
                $liquidadorMaternidad = new LiquidaMaternidad($this->incapacidad);
            $liquidacion['maternidad'] = $liquidadorMaternidad->liquidar();

            return $liquidacion;
        }catch (\Exception $exception){
            throw $exception;
        }
    }

}