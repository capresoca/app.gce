<?php

namespace App\Http\Controllers\RedServicios;

use App\Http\Controllers\Controller;
use App\Models\RedServicios\RsServicio;
use Illuminate\Http\Resources\Json\Resource;

class ServicioController extends Controller
{
    public function getPrimarios()
    {
        //$array      = array();
        $servicios = RsServicio::wherePrimario(1)->with('habilitados.entidad.municipio')->get();
        //$servicios = RsServicio::primarios($afiliado)->get();
        
        return Resource::collection($servicios);
        /*$servicios = RsServicio::wherePrimario(1)->with('habilitados.entidad.municipio','habilitados.planes.contrato')
                        ->whereHas('habilitados.planes.contrato', function ($query) {
                            $query->where('ce_tipocontrato_id',1);
                        })->get();*/
        /*foreach ($servicios as $s) {
            foreach ($s->habilitados as $h) {
                
                $planes = $h->planes;
                
                if($planes==null) {
                    continue;
                }
               
                $m     = $planes->contrato;
                if($m->ce_tipocontrato_id == 1) {
                    if(empty($array['I'.$s->id])) {
                        $array['I'.$s->id]  = $s;
                    }
                }
            }
        }*/
       // var_dump($array);
       /* exit;
        //$servicios = RsServicio::primarios($afiliado)->get();
        foreach ($servicios as $s) {
            $habilitados = $s->habilitados();
            foreach ($habilitados as $h) {
                $planes = $h->planes;
                if($planes==null) {
                    continue;
                }
                foreach ($planes as $p) {
                    $m     = $p->contrato();
                    //foreach ($minuta as $m) {
                        if($m->ce_tipocontrato_id == 1) {
                            if(empty($array['I'.$s->id])) {
                                $array['I'.$s->id]  = $s;
                            }
                        }
                    //}
                }
            }
        }*/

        return Resource::collection($servicios);//Resource::collection(array_values($array)); //Resource::collection($array);

    }



}
