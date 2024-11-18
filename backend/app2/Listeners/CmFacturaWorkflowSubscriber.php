<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 20/02/2019
 * Time: 4:03 PM
 */

namespace App\Listeners;

use Brexis\LaravelWorkflow\Events\GuardEvent;
use Brexis\LaravelWorkflow\Events\TransitionEvent;
use Illuminate\Events\Dispatcher;


class CmFacturaWorkflowSubscriber
{

    /**
     * Handle workflow guard events.
     */
    public function onGuard(GuardEvent $event) {
        $originalEvent = $event->getOriginalEvent();

        $factura = $originalEvent->getSubject();

        if ($factura->revision_finalizada) {
            $originalEvent->setBlocked(true);
        }
    }

    /**
     * Handle workflow leave event.
     */
    public function onLeave($event) {

    }

    /**
     * Handle workflow transition event.
     */
    public function onTransition(TransitionEvent $event) {
//        $originalEvent = $event->getOriginalEvent();

//        $factura = $originalEvent->getSubject();
//        $transition = $originalEvent->getTransition();
//        dd($transition->getTos());

    }

    /**
     * Handle workflow enter event.
     */
    public function onEnter($event) {
    }

    /**
     * Handle workflow entered event.
     */
    public function onEntered($event) {
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            'Brexis\LaravelWorkflow\Events\GuardEvent',
            'App\Listeners\CmFacturaWorkflowSubscriber@onGuard'
        );

        $events->listen(
            'Brexis\LaravelWorkflow\Events\LeaveEvent',
            'App\Listeners\CmFacturaWorkflowSubscriber@onLeave'
        );

        $events->listen(
            TransitionEvent::class,
            'App\Listeners\CmFacturaWorkflowSubscriber@onTransition'
        );

        $events->listen(
            'Brexis\LaravelWorkflow\Events\EnterEvent',
            'App\Listeners\CmFacturaWorkflowSubscriber@onEnter'
        );
        $events->listen(
            'Brexis\LaravelWorkflow\Events\EnteredEvent',
            'App\Listeners\CmFacturaWorkflowSubscriber@onEntered'
        );
    }

}