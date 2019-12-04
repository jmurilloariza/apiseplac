<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TerminaPeriodo extends Mailable
{
    use Queueable, SerializesModels;

    public $para;
    public $asunto;
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($para, $data)
    {
        $this->para = $para;
        $this->asunto = 'Periodo de seguimiento terminado';
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('Mails.terminaPeriodo')
            ->from(env('MAIL_USERNAME'), 'SEPLAC UFPS')
            ->subject('SEPLAC UFPS');
    }
}
