<?php

use App\Jobs\ProcesarRips;
use App\Models\CuentasMedicas\CmRadicado;
use App\Models\Riips\RsRipsRadicado;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CmFacturasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $radicados = DB::select("SELECT rad.* FROM cm_radicados AS rad
                                    
                                    WHERE rad.id = 7484 ");
        
//		$radicados = DB::select("SELECT rad.* FROM cm_radicados AS rad
//                                    LEFT JOIN cm_facturas AS fac ON fac.cm_radicado_id = rad.id
//                                    WHERE fac.cm_radicado_id IS NULL");

//        $radicados = DB::select("SELECT rad.* FROM cm_radicados AS rad
//                                LEFT JOIN cm_facturas AS fac ON fac.cm_radicado_id = rad.id
//                                LEFT JOIN (
//                                		SELECT MAX(a.id) AS id,
//                                			 a.cm_radicado_id AS radicado_id
//                                		FROM cm_radcambios AS a
//                                		LEFT JOIN cm_estadosrads AS b ON b.id = a.cm_estadosrad_id
//                                		GROUP BY a.cm_radicado_id
//                                		) AS z ON z.radicado_id = rad.id
//                                LEFT JOIN cm_radcambios AS cam ON cam.id = z.id
//                                WHERE fac.cm_radicado_id IS NULL AND cam.cm_estadosrad_id IN (1, 2)");

        $data = json_decode(json_encode($radicados),true);

        foreach ($data as $key => $datum) {
            $rips = RsRipsRadicado::find($datum['rs_rip_radicado_id']);
            //if ($rips['id'] === 12893) {
                if ($rips['estado_radicacion'] === 'Confirmado') {
                    $radicado = CmRadicado::find($datum['id']);
                    ProcesarRips::dispatchNow($rips, $radicado);
                }
            //}
        }

    }
}
