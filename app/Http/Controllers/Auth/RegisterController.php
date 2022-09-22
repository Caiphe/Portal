<?php

namespace App\Http\Controllers\Auth;

use App\Content;
use App\Country;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Services\ProductLocationService;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
	/*
		    |--------------------------------------------------------------------------
		    | Register Controller
		    |--------------------------------------------------------------------------
		    |
		    | This controller handles the registration of new users as well as their
		    | validation and creation. By default this controller uses a trait to
		    | provide this functionality without requiring any additional code.
		    |
	*/

	use RegistersUsers;

	/**
	 * Where to redirect users after registration.
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
		$this->middleware('guest');
	}

	/**
	 * Show the application registration form.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function showRegistrationForm(ProductLocationService $productLocationService)
	{
		$locations = $productLocationService->fetch([], 'countries');
		$terms = Content::where('slug', 'terms-and-conditions')->first();

		return view('auth.register', [
			'locations' => $locations,
			'terms' => $terms->body,
		]);
	}

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $data)
	{
		$data['first_name'] = htmlspecialchars($data['first_name'], ENT_QUOTES);
		$data['last_name'] = htmlspecialchars($data['last_name'], ENT_QUOTES);
		$data['email'] = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
		$data['locations'] = Country::pluck('code')->intersect($data['locations'] ?? [])->values()->all();

		return Validator::make($data, [
			'first_name' => ['required', 'string', 'max:255'],
			'last_name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users,email'],
			'terms' => ['required'],
			'password' => [
				'required',
				'string',
				'min:12',
				'confirmed',
				'regex:/[A-Z]/',
				'regex:/[a-z]/',
				'regex:/[0-9]/',
				'regex:/\p{Z}|\p{S}|\p{P}/u'
			],
		]);
	}

	/**
	 * Handle a registration request for the application.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function register(Request $request)
	{
		$data = $request->all();

		$this->validator($data)->validate();

		event(new Registered($user = $this->create($data)));

		if (preg_match('/@mtn\.com$/', $user['email'])) {
			$user->assignRole("internal");
		} else {
			$user->assignRole("developer");
		}

		if ($response = $this->registered($request, $user)) {
            return $response;
		}

		return $request->wantsJson()
			? new Response('', 201)
			: redirect()->route('login')->with('verify', 'A confirmation email has been sent to your email address. Please click on the link in the email and login to verify your email address.');
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
			'password' => $data['password'],
			'profile_picture' => '/storage/profile/profile-' . rand(1, 32) . '.svg',
		]);

		if (isset($data['locations'])) {
			$user->countries()->sync($data['locations']);
		}

		return $user;
	}
}
