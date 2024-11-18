<?php
/**
 * Created by PhpStorm.
 * User: Jorge Eduardo
 * Date: 26/11/2018
 * Time: 9:56 AM
 */

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class AccesoConcedidoEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The demo object instance.
     *
     * @var Demo
     */
    public $info;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($info)
    {
        $this->info = $info;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->subject('Acceso concedido al sistema Capresoca - RIPS')
            ->view('mail.acceso_concedido');
        //Adjuntos  ->attach(storage_path('\app\public\RO3423153178782717653400\errores\errores_validador_rips_radicado_RO3423153178782717653400.xlsx'))
    }
}