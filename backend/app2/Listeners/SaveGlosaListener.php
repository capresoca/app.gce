<?php

namespace App\Listeners;

use App\Events\SaveGlosaEvent;
use App\Http\Requests\CuentasMedicas\FacServiciosGlosasRequest;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SaveGlosaListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(CmGlosa $glosa)
    {
        $this->glosa = $glosa;
//        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param  SaveGlosaEvent  $event
     * @return void
     */
    public function handle(SaveGlosaEvent $event)
    {
        dd('Hey');
    }
}
