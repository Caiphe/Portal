<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GoLiveMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The form data
     */
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $markdownTemplate = "emails.kyc.{$this->data['group']}";
        if(!isset($this->data['files']) || empty($this->data['files'])){
            return $this->markdown($markdownTemplate);
        }

        $m = $this->markdown($markdownTemplate);
        foreach($this->data['files'] as $file) {
            $m->attachFromStorageDisk('local', $file);
        }   

        return $m;
    }
}
