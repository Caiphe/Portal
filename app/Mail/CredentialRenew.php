<?php

namespace App\Mail;

use App\App;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CredentialRenew extends Mailable
{
    use Queueable, SerializesModels;

     /**
     * The App to have it's credentials renewd
     * 
     * @var \App\App
     */
    public $app;

     /**
     * Whether it is the sandbox or production credentials
     * 
     * @var string
     */
    public $type;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(App $app, string $type)
    {
        $this->app = $app;
        $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Renew your credentials')->markdown('emails.credentials.renew');
    }
}
