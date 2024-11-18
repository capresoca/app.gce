<?php

namespace App\Http\Controllers\Aseguramiento;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AtencionUsuario\TbPreferencia;
use App\Models\Aseguramiento\AsPagadore;
use App\Models\LiGestiones\LiAuditoriaPrevia;
use App\Models\TalentoHumano\TbSalarioMinimo;

class LiquidacionAuditoriaPrestacionEconomicaController extends Controller
{
    public function sendLiquidar(Request $request)
    {
        \Log::info('Liquidar '.$request);
        \Log::info('ingreso a sendLiquidar');
        \Log::info('Mapa preferencias'.$this->getMapaPreferencias());
        \Log::info('getRelacionLaboral'.$this->getRelacionLaboral($request->id_afiliado, $request->id_aportante));
        \Log::info('das ende'.$request->tipoLicencia);
        \Log::info('auditoria previa'.$this->getAuditoriaPrevia($request->consecutivo_licencia));
        \Log::info('salario minimo'.$this->getSalarioMinimo($request->fecha_incio_transcripcion));

        if ($this->validacionesLiquidar($request->tipoLicencia)) {
            \Log::info('ok es verdadero');
        } else {
            \Log::info('ok es falso');
        }
        
        if ($request->tipoLicencia === 'Incapacidad enfermedad general') {
            $setPreviaSeleccionada = false;
            $diasAportante = 0;
            $diasAuxilio = 0;
            $porcentajeEps = 0.0;
            $porcentajeEpsMayor90Dias = 0.0;
            $porcentajeEpsMayor180Dias = 0.0;
            $porcentajeEpsMayor540Dias = 0.0;
            $porcentajeAddIndependiente = 0.0;
            $valorTotal = 0.0;
            $ibcTotal = 0.0;

            $li = $this->getAuditoriaPrevia($request->consecutivo_licencia);

            foreach ($this->getMapaPreferencias() as $key => $value) {
                // \Log::info('el id consecutivo_preferencia'.$value->consecutivo_preferencia);
    
                if ($value->tipo_preferencia === 24) {
                    $diasAportante = $value->valor;
                }

                if ($value->tipo_preferencia === 25) {
                    $diasAuxilio = $value->valor;
                }

                if ($value->tipo_preferencia === 26) {
                    $porcentajeEps = $value->valor;
                }

                if ($value->tipo_preferencia === 29) {
                    $porcentajeAddIndependiente = $value->valor;
                }

                if ($value->tipo_preferencia === 56) {
                    $porcentajeEpsMayor90Dias = $value->valor;
                }

                if ($value->tipo_preferencia === 64) {
                    $porcentajeEpsMayor180Dias = $value->valor;
                }

                if ($value->tipo_preferencia === 65) {
                    $porcentajeEpsMayor540Dias = $value->valor;
                }
            }

            $diasAcumuladosActual = 0;
            $diasDescontar = 0;

            // se verifica si hay alguna incapacidad previa señalada
            if(!empty($request->totalDiasLiquidados)){
                $setPreviaSeleccionada = true;
                $diasAcumuladosActual = array_sum($request->totalDiasLiquidados);
            } else {
                $setPreviaSeleccionada = false;
            }

            if ($setPreviaSeleccionada) {
                // si los dias acumulados de la incapacidad previa seÃ±alada + los dias de la incapacidad que se esta liquidando suman mas de 90 dias
                // se usa como porcentaje de eps el parametro porcentajeEpsMayor90Dias
                $diasPrimeros = $diasAcumuladosActual;
                $diasUltimos = $diasAcumuladosActual + $request->dias; // $request->dias=NumeroDiasIncapacidad

                
                //Siempre se le suma 1 a la previa
                $diasPrimeros++;
                
                $ordenPrimero = 0;
                $ordenUltimo = 0;
                if ($diasPrimeros > 90 && $diasPrimeros <= 180) {
                    $ordenPrimero = 1;
                }
                else if ($diasPrimeros > 180 && $diasPrimeros <= 540) {
                    $ordenPrimero = 2;
                }
                else if ($diasPrimeros > 540) {
                    $ordenPrimero = 3;
                }
                
                if ($diasUltimos > 90 && $diasUltimos <= 180) {
                    $ordenUltimo = 1;
                }
                else if ($diasUltimos > 180 && $diasUltimos <= 540) {
                    $ordenUltimo = 2;
                }
                else if ($diasUltimos > 540) {
                    $ordenUltimo = 3;
                }
                
                if($ordenUltimo != $ordenPrimero) {
                    return null;
                }
            }

            $diasAcumuladosActual = $diasAcumuladosActual + $request->dias; // $request->dias=NumeroDiasIncapacidad//FALTA PONER CODIGO DEL PROYECTO JAVA

            if ($diasAcumuladosActual > 90 && $diasAcumuladosActual <= 180) {
                $porcentajeEps = $porcentajeEpsMayor90Dias;
            }
            else if ($diasAcumuladosActual > 180 && $diasAcumuladosActual <= 540) {
                $porcentajeEps = $porcentajeEpsMayor180Dias;
            }
            else if ($diasAcumuladosActual > 540) {
                $porcentajeEps = $porcentajeEpsMayor540Dias;
            }

            if ($porcentajeEps === 0) {
                // se indica que ya se liquido
                // f.setValidarLiquidar(true);
                return null;
            }

            $salarioMinimoDia = $this->getSalarioMinimo($request->fecha_incio_transcripcion);
            $salarioMinimoDia = $salarioMinimoDia[0]->valor/ 30;
            // ------------------------------------------------------------------
            $valorTotal = $salarioMinimoDia * $request->dias;
             \Log::info('el id salarioMinimoDia '.$salarioMinimoDia);
            // ------------------------------------------------------------------
            $ibcMensual = 0; //f.getIbcMensual();ME TOCA BUSCAR DE DONDE SE SACA ESTE IBC MENSUAL

            $ibcReconocer = (($ibcMensual / 30) * $porcentajeEps) / 100;

            if (empty($li)) {
                if ($setPreviaSeleccionada) {
                    $totalDias = $diasAcumuladosActual;
                    $diasDescontar = $totalDias <= $diasAuxilio ? 0 : $diasAuxilio - $diasAcumuladosActual;
                  }
                  else {
                    $diasDescontar = $diasAportante;
                  }
        
                  $ValorDiaReconocer = $ibcReconocer < $salarioMinimoDia ? $salarioMinimoDia : $ibcReconocer;
                  $totalDiasReconocer = 0;
                  
                  // ciclo for pendiente
            } else {
                # code...
                // si no hay una incapacidad previa seÃ±alada se toman los dÃ­as de auxilio de lo contrario se toman los que el aportante asume
                if ($setPreviaSeleccionada) {
                    $totalDias = $diasAcumuladosActual;
                    $diasAuxilio = $totalDias <= $diasAuxilio ? 0 : $diasAuxilio - $diasAcumuladosActual;
                }

                $ValorDiaReconocer = $ibcReconocer < $salarioMinimoDia ? $salarioMinimoDia : $ibcReconocer;
                $totalDiasReconocer = 0;

            }

        }

        if ($request->tipoLicencia === 'Licencia de maternidad' || $request->tipoLicencia === 'Licencia de paternidad' || $request->tipoLicencia === 'Licencia fallecimiento madre') {
            if ($this->validacionesLiquidar($request->tipoLicencia)) {
                foreach ($this->getMapaPreferencias() as $key => $value) {
                    // \Log::info('el id consecutivo_preferencia'.$value->consecutivo_preferencia);
        
                    if ($value->tipo_preferencia === 28) {
                        $diasMinimoIndependiente = $value->valor;
                    }
    
                    if ($value->tipo_preferencia === 29) {
                        $porcentajeAddIndependiente = $value->valor;
                    }
    
                    if ($value->tipo_preferencia === 26) {
                        $porcentajeEps = $value->valor;
                    }
    
                    if ($value->tipo_preferencia === 35) {
                        $diasPartoMultiple = $value->valor;
                    }
    
                    if ($value->tipo_preferencia === 32) {
                        $diasPartoNoviable = $value->valor;
                    }
    
                    if ($value->tipo_preferencia === 33) {
                        $diasMaximoLicenciaPaternidad = $value->valor;
                    }
    
                    if ($value->tipo_preferencia === 55) {
                        $diasMaximoLicenciaMadreMuerta = $value->valor;
                    }
                }
                $valorTotal = 0;
                $diasParametroAcuerdo = 0;
                $diasCotizados = 0;
                $ibcTotal = 0.0;

                $salarioMinimoDia = $this->getSalarioMinimo($request->fecha_incio_transcripcion);
                $salarioMinimoDia = $salarioMinimoDia[0]->valor/ 30;

                $ibcMensual = $this->getRelacionLaboral($request->id_afiliado, $request->id_aportante);

                $ibcReconocer = $ibcMensual / 30; // redondear la cifra
            }
        }
        return response()->json([
            'total_liquidacion' => $valorTotal,
            'dias_acumulados' => $diasAportante
        ]);
    }

    public function getMapaPreferencias()
    {
        return TbPreferencia::all();
    }

    public function getRelacionLaboral($afiliado, $aportante)
    {
        return AsPagadore::leftJoin('as_afiliado_pagador', 'as_afiliado_pagador.as_pagador_id', '=', 'as_pagadores.id')
                         ->leftJoin('as_afiliados', 'as_afiliados.id', '=', 'as_afiliado_pagador.as_afiliado_id')
                         ->where('as_afiliado_id', $afiliado)
                         ->where('as_pagador_id', $aportante)
                         ->get();
    }

    public function getAuditoriaPrevia($consecutivoLicencia)
    {
        return LiAuditoriaPrevia::where('consecutivo_licencia', $consecutivoLicencia)->get();
    }

    public function getSalarioMinimo($fechaInicio)
    {
        $fechaInicioComoEntero = strtotime($fechaInicio);
        $anio= date("Y", $fechaInicioComoEntero);
        return TbSalarioMinimo::where('ano', $anio)->select('valor')->get();
    }

    public function validacionesLiquidar($tipoIncapacidad)
    {
        $ok = true;
        $diasReconocimiento = '';

        foreach ($this->getMapaPreferencias() as $key => $value) {
            // \Log::info('el id consecutivo_preferencia'.$value->consecutivo_preferencia);

            if ($value->tipo_preferencia === 24) {
                $ok = false;
            }

            if ($value->tipo_preferencia === 25) {
                $ok = false;
            }

            if ($value->tipo_preferencia === 26) {
                $ok = false;
            }

            if ($value->tipo_preferencia === 28) {
                $ok = false;
            }

            if ($value->tipo_preferencia === 29) {
                $ok = false;
            }

            if ($value->tipo_preferencia === 30) {
                $ok = false;
            }

            if ($value->tipo_preferencia === 31) {
                $ok = false;
            }

            if ($value->tipo_preferencia === 32) {
                $ok = false;
            }
            if ($value->tipo_preferencia === 33) {
                $ok = false;
            }

            if ($value->tipo_preferencia === 35) {
                $ok = false;
            }

            if ($tipoIncapacidad === 'Licencia fallecimiento madre') {
                if ($value->tipo_preferencia === 55) {
                    $ok = false;
                }
            }

            if ($value->tipo_preferencia === 56) {
                $ok = false;
            }

            if ($value->tipo_preferencia === 64) {
                $ok = false;
            }

            if ($value->tipo_preferencia === 65) {
                $ok = false;
            }

            if ($tipoIncapacidad === 'Incapacidad enfermedad general') {
                if ($value->tipo_preferencia === 56) {
                    $ok = false;
                }

                if ($value->tipo_preferencia === 27) {
                    $ok = false;
                } else {
                    foreach ($this->getMapaPreferencias() as $key => $val) {
                        if ($val->tipo_preferencia === 27) {
                            $diasReconocimiento = $val->valor;
                        }
                    }
                }
            }

        }

        return $ok;
    }

}
