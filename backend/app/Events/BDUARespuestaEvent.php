<?php

namespace App\Events;

use App\Models\Aseguramiento\AsBduarespuesta;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use PhpParser\Node\Expr\Array_;

class BDUARespuestaEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $message;
    private $bduaRespuesta;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Array $message, AsBduarespuesta $bduaRespuesta)
    {
        $this->message = $message;
        $this->respuesta = $bduaRespuesta;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('bdua_respuesta'.$this->bduaRespuesta->id);
    }
}
