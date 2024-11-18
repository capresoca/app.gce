<?php

use App\Http\Repositories\CuentasMedicas\RadicadosRepository;
use App\Models\CuentasMedicas\CmRadicado;
use App\Models\Riips\RsRipsRadicado;
use Illuminate\Database\Seeder;

class CmRadcambioTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $radicados = CmRadicado::all();
        $radicados->load('facturas');

        foreach ($radicados as $key => $radicado) {
            $rip = RsRipsRadicado::select('id','estado_radicacion')->where('id',$radicado->rs_rip_radicado_id)->first();
            if ($rip['estado_radicacion'] === 'Confirmado') {
                $interfaz = new RadicadosRepository();
                $interfaz->guardar([
                    'cm_radicado_id' => $radicado->id,
                    'cm_estadosrad_id' => 1,
                    'gs_usuario_id' =>1,
                    'aceptado' => '1'
                ]);
                $items = $radicado->facturas;
                $totFacturas = count($items);

                $factsAuditoria = array();
                foreach ($items as $key => $item) {
                    if (($item['estado'] !== null || $item['estado'] !== '')) {
                        if (($item['estado'] === 'Asignada')
                            || ($item['estado'] === 'Auditando')
                            || ($item['estado'] === 'Avalada1')
                            || ($item['estado'] === 'Glosada')) {
                            array_push($factsAuditoria, $item);
                        }
                    }
                }

                if ($totFacturas > 0) {
                    if ($totFacturas === count($factsAuditoria)) {
                        $interfaz = new RadicadosRepository();
                        $interfaz->guardar([
                            'cm_radicado_id' => $radicado->id,
                            'cm_estadosrad_id' => 2,
                            'gs_usuario_id' =>1,
                            'aceptado' => '1'
                        ]);
                    }
                }
            }
        }



    }
}
