<?php

namespace App\Console\Commands;

use App\Models\RedServicios\RsPortabilidade;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ActualizarEstadosRsPortabilidades extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:rs_portabilidades';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualizar estados de las portabilidades';

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
        $portabilidades = RsPortabilidade::all();
        foreach ($portabilidades as $key => $portabilidade) {
            $fecha = Carbon::now()->format('Y-m-d');
            if (!$this->check_in_range($portabilidade['fecha_inicio'],$portabilidade['fecha_fin'],$fecha)
                && ($portabilidade->estado === 'Aceptado')) {
                $portable = RsPortabilidade::find($portabilidade['id']);
                $portable->estado = 'Terminada';
                $portable->save();
            }
        }

    }

    private function check_in_range ($fecha_inicio, $fecha_fin, $fecha) {
        return ($fecha >= $fecha_inicio) && ($fecha <= $fecha_fin);
    }
}
