<?php


namespace App\Capresoca\Contratos;


use App\Models\ContratacionEstatal\CeProconminuta;
use App\Models\General\GnEmpresa;

abstract class RenderMinuta
{
    public static function replaceTags(CeProconminuta $minuta)
    {

        $empresa = GnEmpresa::first();

        $minuta->minuta = str_replace('{numero_contrato}',$minuta->numero_contrato,$minuta->minuta);
        $minuta->minuta = str_replace('{objeto}',$minuta->objeto,$minuta->minuta);
        $minuta->minuta = str_replace('{valor}',number_format($minuta->valor,0,',','.'),$minuta->minuta);
        $minuta->minuta = str_replace('{valor_letras}',$minuta->valor_letras,$minuta->minuta);
        $minuta->minuta = str_replace('{objeto}',$minuta->objeto,$minuta->minuta);
        $minuta->minuta = str_replace('{plazo}',$minuta->plazo,$minuta->minuta);

        if($minuta->proceso_contractual && $minuta->proceso_contractual->estudiosPrevios){
            $tarifas = substr($minuta->proceso_contractual->estudiosPrevios->tarifas,3);
            $tarifas = substr($tarifas,0,-4);

            $minuta->minuta = str_replace('{tarifas}',$tarifas,$minuta->minuta);
            $minuta->minuta = str_replace('{lugar_ejecucion}',$minuta->proceso_contractual->estudiosPrevios->lugar_ejecucion,$minuta->minuta);

            $rubro = $minuta->proceso_contractual->estudiosPrevios->imputacionPresupuestal[0]->strgasto->rubro;
            $minuta->minuta = str_replace('{primer_rubro}',$rubro->codigo,$minuta->minuta);

            if($minuta->proceso_contractual->estudiosPrevios->forpagos){
                $formas = '';
                foreach ($minuta->proceso_contractual->estudiosPrevios->forpagos as $key => $forpago) {
                    if($key != 0){
                        $formas .= ', ';
                    }

                    $formas .= $forpago->forma;
                }

                $minuta->minuta = str_replace('{formas_pago}',$formas,$minuta->minuta);
            }

            if($minuta->proceso_contractual->estudiosPrevios->garantias){
                $garantias = '';
                foreach ($minuta->proceso_contractual->estudiosPrevios->garantias as $key => $garantia) {
                    if($key != 0){
                        $garantias .= ', ';
                    }

                    $garantias .= $garantia->garantia->nombre;
                }

                $minuta->minuta = str_replace('{garantias}',$garantias,$minuta->minuta);

            }

            if($minuta->proceso_contractual->estudiosPrevios->actividades){
                $actividades = '';
                foreach ($minuta->proceso_contractual->estudiosPrevios->actividades as $key => $actividad) {
                    if($key != 0){
                        $actividades .= ', ';
                    }

                    $actividades .= $actividad->actividad;
                }

                $minuta->minuta = str_replace('{actividades}',$actividades,$minuta->minuta);
            }


        }

        if($minuta->entidad){
            $minuta->minuta = str_replace('{contratista}',$minuta->entidad->nombre,$minuta->minuta);
            $minuta->minuta = str_replace('{identificacion_contratista}',$minuta->entidad->tercero->identificacion,$minuta->minuta);
            $minuta->minuta = str_replace('{representante_contratista}',$minuta->entidad->replegal,$minuta->minuta);
        }




        if($minuta->cdp){
            $minuta->minuta = str_replace('{numero_cdp}',$minuta->cdp->consecutivo,$minuta->minuta);
            $minuta->minuta = str_replace('{fecha_cdp}',$minuta->cdp->fecha_humana,$minuta->minuta);
        }

        if($minuta->rp){
            $minuta->minuta = str_replace('{numero_rp}',$minuta->rp->consecutivo,$minuta->minuta);
        }else{
            $minuta->minuta = str_replace('{numero_rp}','',$minuta->minuta);
        }

        $minuta->minuta = str_replace('{nombre_rep_capresoca}',$empresa->representante->nombre_completo,$minuta->minuta);
        $minuta->minuta = str_replace('{identificacion_rep_capresoca}',$empresa->representante->identificacion,$minuta->minuta);
        $minuta->minuta = str_replace('{condicion_rep_capresoca}','Gerente',$minuta->minuta);

        $minuta->minuta = str_replace('{usuario}',$minuta->usuario->name,$minuta->minuta);

        $minuta->minuta = str_replace('{juridica}','ADRIANO PEREZ JACOME',$minuta->minuta);

    }


}