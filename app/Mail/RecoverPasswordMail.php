<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecoverPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code;
    public $userName;

    /**
     * Create a new message instance.
     *
     * @param string $code Código de 6 dígitos
     * @param string|null $userName Nome do usuário (opcional)
     */
    public function __construct($code, $userName = null)
    {
        $this->code = $code;
        $this->userName = $userName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Recuperação de Senha - SimHub')
            ->view('emails.recover-password')
            ->with([
                'code' => $this->code,
                'userName' => $this->userName
            ]);
    }
}
