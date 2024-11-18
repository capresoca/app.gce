<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ProcesarRips;
use App\Models\CuentasMedicas\CmRadicado;
use App\Models\Riips\RsRipsRadicado;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProcesaRips extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:procesa_rips';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Procesa RIPS';
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        $radicados = DB::select("
            
            
SELECT a.* FROM cm_radicados a WHERE a.id >= 5295 and a.rs_rip_radicado_id is not null AND NOT EXISTS (
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
    
    /*private function check_in_range ($fecha_inicio, $fecha_fin, $fecha) {
        return ($fecha >= $fecha_inicio) && ($fecha <= $fecha_fin);
    }*/
}
