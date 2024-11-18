<?php

namespace App\Jobs;

use App\Capresoca\Aseguramiento\RedServicios\MigrarRedServiciosIPS;
use App\Models\RedServicios\RsAfiliadoServicio;
use App\RedServicios\RsServhabilitados;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MigrarRedServicios implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        RsAfiliadoServicio::truncate();
        DB::table('red_hm')->whereNotNull('as_afiliado_id')->orderBy('id')->chunk(10000, function ($items)  {
            foreach ($items as $item){
                try{
                    $servHabilitado = RsServhabilitados::firstOrCreate(
                        [
                            'rs_entidad_id' => $item->rs_entidad_id,
                            'rs_servicio_id' => $item->rs_servicio_id
                        ]
                    );
                    RsAfiliadoServicio::create([
                        'as_afiliado_id' => $item->as_afiliado_id,
                        'rs_servhabilitado_id' => $servHabilitado->id
                    ]);
                }catch (\Exception $e){
                    Log::info( "\n **Error** \n  ".$e->getMessage(). "\n \n");
                }
            }
            Log::info("Lote de 10000 Registros Procesado \n");
        });
    }
}
