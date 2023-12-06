<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SyncNotificationErrorMail extends Mailable
{
    use Queueable, SerializesModels;

    public $errorMessage;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Sync Notification Error')->markdown('emails.sync-notification-error');
    }
}
