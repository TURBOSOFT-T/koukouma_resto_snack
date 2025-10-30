<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class forget extends Mailable
{
    use Queueable, SerializesModels;

    public $user,$code;
    public function __construct($user,$code)
    {
        $this->user = $user;
        $this->code = $code;
    }


    public function build()
    {
        return $this->to($this->user)
            ->view('Mail.forget')
            ->subject( ' Réinitialiser le mot de passe')
            ->from("no_reply@benmahmoud-market.shop", config('app.name'))
            ->with([
                'user' => $this->user,
                'code' => $this->code
            ]);

    }
}
