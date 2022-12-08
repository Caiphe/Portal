<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\LogIp;
use Illuminate\Http\Request;
use App\Services\ApigeeUserService;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    protected $maxAttempts = 3;
    protected $decayMinutes = 5;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'developer_id' => $data['developer_id'],
            'password' => $data['password'],
            'profile_picture' => '/storage/profile/profile-' . rand(1, 32) . '.svg',
        ]);

        if (isset($data['locations'])) {
            $countryIds = Country::whereIn('code', $data['locations'])->pluck('id');
            $user->countries()->sync($countryIds);
        }

        return $user;
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        LogIp::updateOrCreate(
            ['user_id' => $request->user()->id],
            ['ip' => $request->ip()]
        );

        if (!is_null($user->developer_id)) return;

        ApigeeUserService::setupUser($user);
    }
}
