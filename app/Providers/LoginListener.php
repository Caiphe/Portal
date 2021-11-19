<?php

namespace App\Providers;

use App\Notifications\NewDevice;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LoginListener
{
    public Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(Login $event): void
    {
        if ($event->user) {
            $user = $event->user;
            $ip = $this->request->ip();
            $userAgent = $this->request->userAgent();
            $known = $user->authentications()->whereIpAddress($ip)->whereUserAgent($userAgent)->first();
            $newUser = Carbon::parse($user->created_at)->diffInMinutes(Carbon::now()) < 1;

            $log = $user->authentications()->create([
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'login_at' => now(),
                'login_successful' => true,
                'location' => null,
            ]);

            if (!$known && !$newUser) {
                $user->notify(new NewDevice($log));
            }
        }
    }
}
