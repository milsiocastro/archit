<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FormularioAMail extends Mailable
{
    use Queueable, SerializesModels;

    public $formularioA;

    public function __construct($formularioA)
    {
        $this->formularioA = $formularioA;
    }

    public function build()
    {
        return $this->subject($this->formularioA->asunto)->view('emails.formulario');
    }
}
