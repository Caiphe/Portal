<?php

namespace App\Mail;

use App\App;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class KycStatusUpdate extends Mailable
{
    use Queueable, SerializesModels;

    public $developer;
    public $app;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $developer, App $app)
    {
        $this->developer = $developer;
        $this->app = $app;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.kyc.status-update');
    }
}
