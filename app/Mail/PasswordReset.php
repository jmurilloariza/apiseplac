<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordReset extends Mailable
{
    use Queueable, SerializesModels;

    public $para;
    public $host;
    public $k;
    public $asunto;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($para, $token)
    {
        $this->para = $para;
        $this->host = 'localhost:4200/#';
        $this->k = base64_encode('email='.$para.'?token='.$token);
        $this->asunto = 'Restablecimiento de clave personal';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('Mails.passwordReset')
            ->from(env('MAIL_USERNAME'), 'SEPLAC UFPS')
            ->subject('SEPLAC UFPS');
    }
}
