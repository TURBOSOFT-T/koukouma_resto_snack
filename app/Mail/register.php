<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class register extends Mailable
{
    use Queueable, SerializesModels;

  
    public $user;
    public function __construct($user)
    {
        $this->user = $user;
    }


    public function build()
    {
        return $this->to($this->user->email)
            ->view('Mail.register')
            ->subject('Bienvenue sur Ben Madmoud Market')
            ->from("no_reply@benmahmoud-market.shop", "Ben Madmoud Market")
            ->with([
                'user' => $this->user,
            ]);

    }
}
