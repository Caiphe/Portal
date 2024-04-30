<?php

namespace App\Jobs;
use App\Notification;
use Illuminate\Bus\Queueable;
use App\Services\SyncAppService;
use App\Mail\SyncNotificationMail;
use App\Services\SyncProductService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use App\Mail\SyncNotificationErrorMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SyncAll implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $user;
    public $tries = 1;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->user = Auth()->user();
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        Notification::create([
            "user_id" => $this->user->id,
            "notification" => "The Sync process has started, an email will be sent upon completion with all the details.",
        ]);

        $syncProductService = new SyncProductService();
        $productsResult = $syncProductService->syncProducts();

        $syncAppService = new SyncAppService();
        $appsResult = $syncAppService->syncApps();

        // Data to be passed to the sync notification email
        $deletedProducts = $productsResult['deletedProducts'];
        $totalProducts = $productsResult['totalProducts'];
        $addedProducts = $productsResult['addedProducts'];
        $totalApps = $appsResult['totalApps'];
        $noCredsApps = $appsResult['no-creds'] ?? null;
        $noProdApps = $appsResult['no-products'] ?? null;

        Mail::to($this->user->email)->send(new SyncNotificationMail(
            $deletedProducts, $totalProducts, $addedProducts, $totalApps, $noCredsApps, $noProdApps
        ));

        Notification::create([
            "user_id" => $this->user->id,
            "notification" => "The Sync completed successfully, an email has been sent to you with all the details.",
        ]);
    }

    public function failed()
    {
        $message = "The sync process has failed please try again.";

        Notification::create([
            "user_id" => $this->user->id,
            "notification" => "The Sync process has failed. Please proceed to the dashboard and try again.",
        ]);

        Mail::to($this->user->email)->send(new SyncNotificationErrorMail($message));
    }
}
