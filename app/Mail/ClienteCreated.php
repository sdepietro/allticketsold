<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClienteCreated extends Mailable
{
    use Queueable, SerializesModels;
	
	public $email;
    public $contraseña;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $contraseña)
    {
        $this->email = $email;
        $this->contraseña = $contraseña;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Detalles de tu cuenta')
                    ->view('Emails.cliente_created'); // vista para el correo
    }
}
