<?php

namespace App\Jobs;

use App\Capresoca\RecaudosSOI\PersistirPlanilla;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PersistirHistoricoPlanillasFromDirectory implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $path;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $dir = opendir($this->path);
        while ($filePath = readdir($dir)) {
            if ($filePath !== '.' && $filePath !== '..') {
                if(!$this->esValido($filePath)) continue;
                $pila = new PersistirPlanilla($this->path.'/'.$filePath);
                $pila->handle();
            }
        }
    }

    private function esValido($file)
    {
        $re = '/_IR_|_I_/m';
        $str = $file;

        preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);
        return count($matches) > 0;
    }

}
