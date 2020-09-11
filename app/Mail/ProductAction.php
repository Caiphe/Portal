<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProductAction extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The app that the product is connected to.
     * 
     * @var object
     */
    public $app;

    /**
     * The product that is being approved/revoked
     * 
     * @var array
     */
    public $data;

    /**
     * The current user that is is approving/revoking the product
     * 
     * @var object
     */
    public $currentUser;

    /**
     * Create a new message instance.
     *
     * @param      object  $app          The application
     * @param      array   $data         The data (product and action)
     * @param      object  $currentUser  The current user
     *
     * @return     void
     */
    public function __construct($app, $data, $currentUser)
    {
        $this->app = $app;
        $this->data = $data;
        $this->currentUser = $currentUser;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->currentUser->email;
        $subject = "Product {$this->data['action']}d by {$this->currentUser->full_name}";

        return $this->withSwiftMessage(function ($message) use ($email) {
            $message->getHeaders()->addTextHeader('Reply-To', $email);
        })
        ->subject($subject)
        ->markdown('emails.product-action');
    }
}
