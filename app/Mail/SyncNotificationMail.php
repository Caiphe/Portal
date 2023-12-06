<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SyncNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $deletedProducts;
    public $totalProducts;
    public $addedProducts;
    public $totalApps;
    public $noProdApps;
    public $noCredsApps;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        $deletedProducts, $totalProducts, $addedProducts, $totalApps, $noCredsApps, $noProdApps
    )
    {
        $this->deletedProducts = $deletedProducts;
        $this->totalProducts = $totalProducts;
        $this->addedProducts = $addedProducts;
        $this->totalApps = $totalApps;
        $this->noCredsApps = $noCredsApps;
        $this->noProdApps = $noProdApps;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Sync All Notification')->markdown('emails.sync-notification');
    }
}
