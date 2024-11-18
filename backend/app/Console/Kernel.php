<?php

namespace App\Console;

use App\Capresoca\Compensacion\GenerarLiquidacionesVecidas;
use App\Jobs\Mipres\DescargarNovedades;
use App\Jobs\Mipres\DescargarPrescripciones;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\ActualizarEstadosRsPortabilidades',
        'App\Console\Commands\ProcesaTraslados',
        'App\Console\Commands\ProcesaArchivosRsRC',
		'App\Console\Commands\ProcesaRips'
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(new GenerarLiquidacionesVecidas)
                  ->daily();
        $schedule->job(new DescargarPrescripciones,'archivos')->dailyAt('06:00');
        $schedule->job(new DescargarPrescripciones,'archivos')->dailyAt('09:45');
        $schedule->job(new DescargarPrescripciones,'archivos')->dailyAt('13:00');
        $schedule->job(new DescargarPrescripciones,'archivos')->dailyAt('16:00');
//        $schedule->job(new DescargarPrescripciones,'archivos')->dailyAt('11:57');
        $schedule->job(new DescargarPrescripciones(today()->subDay()->toDateString()),'archivos')->dailyAt('01:00');
        $schedule->job(new DescargarNovedades(today()->subDay()->toDateString()),'archivos')->dailyAt('01:00');
        $schedule->command('update:rs_portabilidades')->dailyAt('23:59');
        $schedule->command('update:procesa_traslados')->dailyAt('06:00');
        //Log::info('Entra a las tareas');
        $schedule->command('update:procesa_archivo_rs_rc')->dailyAt('07:44');
		$schedule->command('update:procesa_rips')->everyTenMinutes();        
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
