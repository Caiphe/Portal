<?php

namespace App\Http\Controllers\Auth;

use App\Country;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Product;
use App\Services\ApigeeService;
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
    public function showRegistrationForm()
    {
        $productLocations = Product::isPublic()
            ->WhereNotNull('locations')
            ->Where('locations', '!=', 'all')
            ->select('locations')
            ->get()
            ->implode('locations', ',');

        return view('auth.register', ['locations' => array_unique(explode(',', $productLocations))]);
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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
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

        $apigeeDeveloper = ApigeeService::post('developers', [
            "email" => $data['email'],
            "firstName" => $data['first_name'],
            "lastName" => $data['last_name'],
            "userName" => $data['first_name'] . $data['last_name'],
        ])->json();
        
        if (isset($apigeeDeveloper['code'])) {
            return redirect('/register')
                ->withErrors(['email' => $apigeeDeveloper['message']])
                ->withInput();
        }

        $data['developer_id'] = $apigeeDeveloper['developerId'];

        event(new Registered($user = $this->create($data)));

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
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
        ]);

        if (isset($data['locations'])) {
            $countryIds = Country::whereIn('code', $data['locations'])->pluck('id');
            $user->countries()->sync($countryIds);
        }

        $imageName = 'public/profile/' . base64_encode('jsklaf88sfjdsfjl' . $user->id) . '.png';
        \Storage::copy('public/profile/profile.png', $imageName);

        return $user;
    }
}
