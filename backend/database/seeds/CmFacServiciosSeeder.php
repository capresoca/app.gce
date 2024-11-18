<?php

use App\Jobs\ProcesarRips;
use App\Models\CuentasMedicas\CmRadicado;
use App\Models\Riips\RsRipsRadicado;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CmFacServiciosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * $radicados = DB::select("SELECT rad.* FROM cm_radicados AS rad
                                    LEFT JOIN cm_facturas AS fac ON fac.cm_radicado_id = rad.id
                                    WHERE fac.cm_radicado_id IS NULL AND rad.consecutivo = 4907"); }
		
$radicados = DB::select(
"SELECT rad.* 
					FROM cm_radicados AS rad WHERE id IN (
 SELECT DISTINCT fac.cm_radicado_id 
                                        
					FROM cm_facturas AS fac
                                        LEFT JOIN cm_facservicios AS serv ON serv.cm_factura_id = fac.id
 WHERE
 serv.cm_factura_id IS NULL )" 
 
 );


"SELECT rad.* 
					FROM cm_radicados AS rad WHERE id IN ( 4581 )"  
 
		 
     * @return void 
     */
    public function run()
    {
				$radicados = DB::select("
				

SELECT a.* FROM cm_radicados a WHERE a.id >= 7539 AND a.id <= 8434 and a.rs_rip_radicado_id is not null AND NOT EXISTS (
SELECT id FROM cm_facservicios WHERE cm_factura_id 
IN (SELECT id FROM cm_facturas WHERE cm_radicado_id = a.id )
)				
				");  
          
        $data = json_decode(json_encode($radicados),true);
         
        foreach ($data as $key => $datum) {
            $rips = RsRipsRadicado::find($datum['rs_rip_radicado_id']);
            if ($rips->estado_radicacion === 'Confirmado') {
                $radicado = CmRadicado::find($datum['id']);
                ProcesarRips::dispatchNow($rips, $radicado);
            }
        } 
    }
}
