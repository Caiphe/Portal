<?php

namespace App\Jobs;

use App\Mail\SyncNotificationErrorMail;
use Illuminate\Bus\Queueable;
use App\Mail\SyncNotificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\SyncAppService;
use App\Services\SyncProductService;

class SyncAll implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $user;
    public $tries = 2;

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
    }

    public function failed(\Exception $e)
    {
        Mail::to($this->user->email)->send(new SyncNotificationErrorMail($e->getMessage()));
    }
}
