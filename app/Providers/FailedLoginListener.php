<?php

namespace App\Providers;

use App\Notifications\FailedLogin;
use Illuminate\Auth\Events\Failed;
use Illuminate\Http\Request;

class FailedLoginListener
{
    public Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(Failed $event): void
    {
        if ($event->user) {
            $log = $event->user->authentications()->create([
                'ip_address' => $ip = $this->request->ip(),
                'user_agent' => $this->request->userAgent(),
                'login_at' => now(),
                'login_successful' => false,
                'location' => null,
            ]);

            $event->user->notify(new FailedLogin($log));
        }
    }
}
