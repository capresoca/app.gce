<?php


namespace App\Listeners;


use Brexis\LaravelWorkflow\Events\TransitionEvent;
use Brexis\LaravelWorkflow\Events\WorkflowSubscriber;

class CuentasWorkflowSubscriber extends WorkflowSubscriber
{

    public function onTransition(TransitionEvent $event)
    {

    }

    public function subscribe($events)
    {
        $events->listen(
            'Brexis\LaravelWorkflow\Events\TransitionEvent',
            'App\Listeners\CuentasWorkflowSubscriber@onTransition'
        );
    }
}