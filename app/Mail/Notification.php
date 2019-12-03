<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * @author jmurilloariza - jefersonmanuelma@ufps.edu.co 
 * @version 1.0
 */

class Notification extends Mailable
{
    use Queueable, SerializesModels;

    public $para;
    public $asunto;
    public $mensaje;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($para, $asunto, $mensaje)
    {
        $this->para = $para;
        $this->asunto = $asunto;
        $this->mensaje = $mensaje;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('Mails.notificacion')
            ->from(env('MAIL_USERNAME'), 'SEPLAC UFPS')
            ->subject('SEPLAC UFPS');
    }
}
