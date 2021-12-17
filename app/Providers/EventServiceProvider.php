<?php

namespace App\Providers;

use App\Listeners\VerifiedListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\OtherDeviceLogout;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Verified::class => [
            VerifiedListener::class
        ],
        Login::class => [
            LoginListener::class
        ],
        Logout::class => [
            LogoutListener::class
        ],
        Failed::class => [
            FailedLoginListener::class
        ],
        OtherDeviceLogout::class => [
            OtherDeviceLogoutListener::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
