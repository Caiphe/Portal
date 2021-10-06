<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

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
        \Mpociot\Teamwork\Events\UserJoinedTeam::class => [
            \App\Events\Listeners\Teams\JoinTeamInviteListener::class,
        ],
        \Mpociot\Teamwork\Events\UserLeftTeam::class => [
            \App\Events\Listeners\Teams\UserLeftTeamListener::class,
        ],
        \Mpociot\Teamwork\Events\UserInvitedToTeam::class => [
            \App\Events\Listeners\Teams\UserInvitedToTeamListener::class
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
