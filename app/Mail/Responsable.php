<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Responsable extends Mailable
{
    use Queueable, SerializesModels;

    public $para;
    public $asunto;
    public $proyecto;
    public $plan;
    public $actividad;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($para, $actividad)
    {
        $this->para = $para;
        $this->asunto = 'Asignado como reponsable';
        $this->plan = $actividad['proyecto']['planes_proyectos']['plan'];
        $this->proyecto = $actividad['proyecto'];
        $this->actividad = $actividad;
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
