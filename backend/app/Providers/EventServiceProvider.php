<?php

namespace App\Providers;

use Brexis\LaravelWorkflow\Events\GuardEvent;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
//        'App\Events\Event' => [
//            'App\Listeners\EventListener',
//        ],
        'App\Events\SaveGlosaEvent' => [
            'App\Listeners\SaveGlosaListener',
        ]
    ];
    protected $subscribe = [
        'App\Listeners\CmFacturaWorkflowSubscriber'
    ];


    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        //
    }
}
