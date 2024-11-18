<?php

namespace App\Events;

use App\Models\Aseguramiento\AsBduaproceso;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BduaProcesosEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var array
     */
    private $message;
    /**
     * @var AsBduaproceso
     */
    private $proceso;

    public $broadcastQueue = 'pushs';


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Array $message, AsBduaproceso $proceso)
    {
        //
        $this->message = $message;
        $this->proceso = $proceso;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('bdua-proceso.'.$this->proceso->id);
    }

    public function broadcastWith()
    {
        return $this->message;
    }
}
