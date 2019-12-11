<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * @author jmurilloariza - jefersonmanuelma@ufps.edu.co 
 * @version 2.0
 */

class Responsable extends Mailable
{
    use Queueable, SerializesModels;

    public $para;
    public $asunto;
    public $proyecto;
    public $plan;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($para, $proyectoUsuario)
    {
        $this->para = $para;
        $this->asunto = 'Asignado como reponsable';
        $this->plan = $proyectoUsuario['proyecto']['planes_proyectos'][0]['plan'];
        $this->proyecto = $proyectoUsuario['proyecto'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('Mails.responsable')
            ->from(env('MAIL_USERNAME'), 'SEPLAC UFPS')
            ->subject('SEPLAC UFPS');
    }
}
