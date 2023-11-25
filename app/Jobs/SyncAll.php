<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Mail\SyncNotificationMail;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SyncAll implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $user =  Auth()->user();
    }

    /**
     * Execute the job.
     */
    public function handle($user)
    {
        Artisan::call('sync:products');
        // Artisan::call('sync:apps');

        Mail::to($user->email)->send(new SyncNotificationMail);

        // return Artisan::output();
    }
}
