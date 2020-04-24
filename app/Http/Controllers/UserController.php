<?php

namespace App\Http\Controllers;

use App\Country;
use App\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UserController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show() {
		$user = \Auth::user();
		$user->load('countries');

		$productLocations = Product::isPublic()
			->WhereNotNull('locations')
			->Where('locations', '!=', 'all')
			->select('locations')
			->get()
			->implode('locations', ',');

		return view('templates.user.show', [
			'user' => $user,
			'userLocations' => $user->countries->pluck('code')->toArray(),
			'locations' => array_unique(explode(',', $productLocations)),
		]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, User $user) {
		$validateOn = [
			'first_name' => 'required',
			'second_name' => 'required',
		];

		if ($request->email !== $user->email) {
			$validateOn = array_merge($validateOn, ['email' => 'email|required|unique:users,email']);
		}

		if ($request->password !== null) {
			$validateOn = array_merge($validateOn, ['password' => 'confirmed']);
		}

		$validatedData = $request->validate($validateOn);

		if (isset($validatedData['password'])) {
			$validatedData['password'] = bcrypt($validatedData['password']);
		}

		$user->update($validatedData);

		if ($request->has('locations')) {
			$countryIds = Country::whereIn('code', $request->locations)->pluck('id');
			$user->countries()->sync($countryIds);
		}

		\Session::flash('alert', 'Success:Your profile has been updated.');

		return redirect()->back();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		//
	}

	public function updateProfilePicture(Request $request) {
		$imageName = 'profile/' . base64_encode('jsklaf88sfjdsfjl' . $request->user()->email) . '.png';

		$image = Image::make($request->file('profile'))
			->fit(452, 452, function ($constraint) {
				$constraint->aspectRatio();
				$constraint->upsize();
			});

		$success = \Storage::disk('public')->put($imageName, (string) $image->encode('png', 95));

		$request->user()->update(['profile_picture' => '/storage/' . $imageName]);

		return response()->json([
			'success' => $success,
		]);
	}
}
