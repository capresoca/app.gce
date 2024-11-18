<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/10/2018
 * Time: 5:23 PM
 */

namespace App\Capresoca\Compensacion;


use App\Capresoca\LeerCsv;
use App\Compensacion\CpAporte;
use App\Compensacion\CpLiquidacione;
use App\Compensacion\CpPlanilla;
use App\Http\Requests\Compensacion\ArchivosaporteRequest;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsAfiliadoPagador;
use App\Models\Aseguramiento\AsPagadore;
use App\Models\Compensacion\CpArchivosaporte;
use App\Models\Compensacion\CpFechaslimite;
use App\Models\General\GnTipdocidentidade;
use App\Traits\CarbonColombia;
use App\Traits\ToolsTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ProcesadorAportes
{
    use LeerCsv;
    protected $archivosAportes;
    public function __construct(CpArchivosaporte $archivosaporte)
    {
        $this->archivosAportes = $archivosaporte;
    }

    public function procesar(){
        $this->procesarFinanciero();
        $this->procesarPila();

    }

    private function procesarFinanciero()
    {
        $readerFinanciero = $this->leerFromUrlS3($this->archivosAportes->archivoFinanciero->ruta);
        $data = $readerFinanciero->getRecords();

        foreach ($data as $planilla){

            $aportante = AsPagadore::firstOrCreate(
                [
                    'identificacion' => $planilla[1],
                ],
                [
                    'razon_social' => $planilla[2]
                ]
            );


            $planilla = CpPlanilla::firstOrCreate(
                [
                    'aportante_id' => $aportante->id,
                    'numero' => $planilla[4],
                    'periodo' => $planilla[5],
                ],
                [
                    'registros' => $planilla[7]
                ]
            );
        }
    }

    private function procesarPila()
    {
        $readerFinanciero = $this->leerFromUrlS3($this->archivosAportes->archivoPila->ruta);
        $data = $readerFinanciero->getRecords();

        foreach ($data as $aporte){
            try{


                $periodoPlanilla = str_replace('-','',$aporte[2]);

                $planilla = CpPlanilla::where('numero', $aporte[1])
                    ->where('periodo',$periodoPlanilla)->first();

                if(!$planilla){
                    Log::info('no se encontro la planilla'. $aporte[1].' - '.$periodoPlanilla);
                    continue;
                }


                $afiliado = $this->getAfiliado($aporte[6],$aporte[7]);

                if(!$afiliado){
                    Log::info('No se encontro el afiliado: '.$aporte[6].','.$aporte[7]);
                    continue;
                }



                $relacionLaboral = AsAfiliadoPagador::updateOrCreate(
                    [
                        'as_afiliado_id' => $afiliado->id,
                        'as_pagador_id' => $planilla->aportante_id,

                    ],
                    [
                        'ibc' => $this->calcularIbc($aporte),
                        'fecha_vinculacion' => $this->calcularFechaIngreso($aporte),
                        'estado' => 'Activo',
                        'tipo_cotizante' => $aporte[11]
                    ]
                );


                $liquidacion = CpLiquidacione::updateOrCreate(
                    [
                        'relacion_laboral_id' => $relacionLaboral->id,
                        'periodo' => $aporte[2],
                    ],
                    [
                        'ibc' => $this->calcularIbc($aporte),
                        'ingreso' => $this->tieneIngreso($aporte)
                    ]
                );

                if(!$this->tieneRetiro($aporte)){
                    $this->crearLiquidacionesVencidas($relacionLaboral, $aporte);
                }else{
                    $relacionLaboral->estado = 'Inactivo';
                    $relacionLaboral->save();
                    $tresPeriodosAdelante = Carbon::parse($aporte[2])->addMonths(3)->format('Y-m');
                    $relacionLaboral->liquidaciones()->where('periodo','>', $tresPeriodosAdelante)
                        ->where('dias_pagados',0)
                        ->doesntHave('aportes')
                        ->delete();
                    $liquidacion->retiro = 1;
                }

                CpAporte::updateOrCreate([
                    'planilla_id' => $planilla->id,
                    'liquidacion_id' => $liquidacion->id,
                    'dias' => $aporte[32],
                    'fecha_pago' => ToolsTrait::homologarFecha($aporte[3]),
                    'ingreso' => $this->tieneIngreso($aporte),
                    'retiro' => $this->tieneRetiro($aporte),
                    'ibc' => $aporte[34],
                    'cotizacion' => $aporte[36]
                ],[
                    'tipo_cotizante' => $aporte[11]
                ]);

                $liquidacion->dias_pagados += $aporte[32];
                $liquidacion->ingreso = $this->tieneIngreso($aporte);
                $liquidacion->save();
            }catch (\Exception $exception){
                Log::error($exception->getMessage() . 'Linea: ' . $exception->getLine());
                continue;
            }
        }

        $this->archivosAportes->estado = 'Procesado';

        $this->archivosAportes->save();

        DepuracionRetiros::run();
        UnificacionAportesPagador::run();

    }


    public function crearLiquidacionesVencidas($relacionLaboral, $aporte)
    {

        $mes_liquidacion = CarbonColombia::createFromFormat('Y-m',$aporte[2]);

        $mes_liquidacion->firstOfMonth();

        $mes_liquidacion->addMonth();


        $mes_liquidacion->addBussinessDays($this->getDiaHabilPago($relacionLaboral) - 1);

        $corte = today()->subMonth();

        while($corte->greaterThan($mes_liquidacion)){
            $periodo = $mes_liquidacion->format('Y-m');


            $liquidacion = CpLiquidacione::firstOrCreate(
                [
                    'relacion_laboral_id' => $relacionLaboral->id,
                    'periodo' => $periodo
                ],
                [
                    'ibc' => $this->calcularIbc($aporte)
                ]
            );

            $mes_liquidacion->addMonth();
        }
    }

    protected function getAfiliado(String $tipoIdentificacion, String $identificacion)
    {
        $tipoId = GnTipdocidentidade::where('abreviatura', $tipoIdentificacion)->first();

        $identificacion = [
            'identificacion' => $identificacion,
            'tipoId' => $tipoId->id
        ];

        $afiliado = AsAfiliado::AfiliadoPorIdentificacion($identificacion)->first();

        if(!$afiliado){
            $afiliado = AsAfiliado::where('identificacion', $identificacion['identificacion'])->first();
        }

        return $afiliado;
    }

    private function calcularIbc($aporte)
    {
        return floor($aporte[32] ? ($aporte[34]/$aporte[32])*30 : $aporte[33]);
    }

    private function calcularFechaIngreso($aporte)
    {
        $fecha_periodo = Carbon::createFromFormat('Y-m', $aporte[2]);

        if($aporte[24]){
            return $fecha_periodo->firstOfMonth()->addDays(30)->subDays($aporte[32])->toDateString();
        }

        return $fecha_periodo->firstOfMonth()->toDateString();
    }

    private function tieneRetiro($aporte)
    {
        if($aporte[25]==='X' || $aporte[25] === 'x') return 1;

        return 0;
    }

    private function tieneIngreso($aporte)
    {
        if($aporte[24]==='X' || $aporte[24] === 'x') return 1;

        return 0;
    }

    public function getDiaHabilPago(AsAfiliadoPagador $relacionLaboral)
    {
        $ultDigitos = $this->getDosUltimosDigitosIdentificacion($relacionLaboral);

        return CpFechaslimite::select('dia_habil')->where([
            ['desde','<=', (int)$ultDigitos],
            ['hasta','>=',(int)$ultDigitos]
        ])->first()->dia_habil;
    }

    public function getDosUltimosDigitosIdentificacion(AsAfiliadoPagador $relacionLaboral)
    {
        if($relacionLaboral->esIndependiente()){
            return substr($relacionLaboral->afiliado->identificacion,-2);
        }

        return substr($relacionLaboral->pagador->identificacion,-2);
    }

}
