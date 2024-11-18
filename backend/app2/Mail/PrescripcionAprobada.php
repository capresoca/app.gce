<?php

namespace App\Mail;

use App\Models\Mipres\MpPrescripcione;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PrescripcionAprobada extends Mailable
{
    use Queueable, SerializesModels;
    protected $prescripcion;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(MpPrescripcione $prescripcion)
    {
        $this->prescripcion = $prescripcion;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Prescripcion '.$this->prescripcion->NoPrescripcion.' '.' '.$this->prescripcion->TipoIDPaciente.$this->prescripcion->NroIDPaciente.' '.$this->prescripcion->paciente)
                    ->view('mail.prescripcion_aprobada',['prescripcion' => $this->prescripcion]);
    }
}
