<?php

namespace App\Http\Controllers\Auth;

use App\Content;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Services\ApigeeUserService;
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
		[$products, $locations] = $productLocationService->fetch();
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
		return Validator::make($data, [
			'first_name' => ['required', 'string', 'max:255'],
			'last_name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users'],
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

		$data = ApigeeUserService::setupUser($data);

		event(new Registered($user = $this->create($data)));

		if (preg_match('/@mtn\.com$/', $user['email'])) {
			$user->assignRole("internal");
		} else {
			$user->assignRole("developer");
		}

		$this->guard()->login($user);

		if (!$user->exists) {
            return $this->registered($request, $user);
		}

		return $request->wantsJson()
			? new Response('', 201)
			: redirect($this->redirectPath());
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
			'password' => Hash::make($data['password']),
			'profile_picture' => '/storage/profile/profile-' . rand(1, 32) . '.svg',
		]);

		if (isset($data['locations'])) {
			$user->countries()->sync($data['locations']);
		}

		return $user;
	}
}
