<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Services\ApigeeUserService;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

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
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $drupalUserCheck = DB::table('drupal_users')
            ->leftJoin('users', 'drupal_users.email', '=', 'users.email')
            ->select('users.email as user_email', 'drupal_users.email', 'drupal_users.first_name', 'drupal_users.last_name')
            ->where('drupal_users.email', $request->get($this->username()))->first();

        if ($drupalUserCheck === null || $drupalUserCheck->user_email !== null) {
            throw ValidationException::withMessages([
                $this->username() => [trans('auth.failed')],
            ]);
        }

        $data = ApigeeUserService::setupUser((array)$drupalUserCheck);

        $data['password'] = date("imsYdH");
        $user = $this->create($data);
        $user->assignRole("developer");

        $token = Password::createToken($user);
        $user->sendPasswordResetNotification($token);

        return redirect()->route('password.reset', ['token' => $token])->with('type', 'drupal');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data) {
        $imageName = base64_encode('jsklaf88sfjdsfjl' . $data['email']) . '.svg';
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'developer_id' => $data['developer_id'],
            'password' => Hash::make($data['password']),
            'profile_picture' => '/storage/profile/' . $imageName,
            'email_verified_at' => date('Y-m-d H:i:s'),
        ]);

        if (isset($data['locations'])) {
            $countryIds = Country::whereIn('code', $data['locations'])->pluck('id');
            $user->countries()->sync($countryIds);
        }

        $imagePath = 'public/profile/' . $imageName;
        if (\Storage::exists($imagePath)) {
            \Storage::delete($imagePath);
        }
        \Storage::copy('public/profile/profile-' . rand(1, 32) . '.svg', $imagePath);

        return $user;
    }
}
