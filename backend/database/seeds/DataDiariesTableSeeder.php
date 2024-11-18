<?php

use App\Http\Repositories\CuentasMedicas\RadicadosRepository;
use App\Models\CuentasMedicas\CmFactura;
use App\Models\CuentasMedicas\CmRadicado;
use App\Traits\CuentasMedicasTrazaTrait;
use Illuminate\Database\Seeder;

class DataDiariesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $radicados = CmRadicado::with('ripRadicado')->get();

        foreach ($radicados as $radicado) {
            if ($radicado->tipo_facturacion === 'Capita' && (! is_null($radicado->rs_contrato_id))) {
                $factura = CmFactura::where('cm_radicado_id',$radicado->id)->first();
                CuentasMedicasTrazaTrait::registrarServices($factura,$radicado->rs_contrato_id);
            }
        }
        // Este Seeder puede ser reutilizado.
        //DB::statement("UPDATE `cm_manglosas` SET `capita` = '1' WHERE (`id` = '27')");
        //DB::statement("UPDATE `cm_manglosas` SET `capita` = '1' WHERE (`id` = '28')");
        //DB::statement("UPDATE `cm_manglosas` SET `capita` = '1' WHERE (`id` = '29')");

//        $radicados = CmRadicado::where('rs_rip_radicado_id','=',null)->get();
//
//        foreach ($radicados as $radicado) {
//            $estadoRad = new RadicadosRepository();
//            $estadoRad->guardar([
//                'cm_radicado_id' => $radicado->id,
//                'cm_estadosrad_id' => 7,
//                'gs_usuario_id' => 1,
//                'aceptado' => '1',
//            ]);
//        }


    }
}
