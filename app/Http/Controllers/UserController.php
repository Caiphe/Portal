<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use App\Services\TwofaService;
use Google2FA;

class UserController extends Controller
{
	/**
	 * Display the specified resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request)
	{
		$user = $request->user();
		$user->load('countries');

		$key = $user['2fa'] ?? TwofaService::getSecretKey();
		$inlineUrl = TwofaService::getInlineUrl($key, $user->email);

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
			'key' => $key,
			'inlineUrl' => $inlineUrl,
		]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request)
	{
		$validateOn = [
			'first_name' => 'required',
			'last_name' => 'required',
		];

		$user = $request->user();

		if ($request->email !== $user->email) {
			$validateOn = array_merge($validateOn, ['email' => 'email:filter|required|unique:users,email']);
		}

		if ($request->password !== null) {
			$validateOn = array_merge($validateOn, ['password' => [
				'confirmed',
				'min:12',
				'regex:/[A-Z]/',
				'regex:/[a-z]/',
				'regex:/[0-9]/',
				'regex:/\p{Z}|\p{S}|\p{P}/u'
			]]);
		}

		$validatedData = $request->validate($validateOn);

		if (isset($validatedData['password'])) {
			$validatedData['password'] = bcrypt($validatedData['password']);
		}

		$validatedData['first_name'] = filter_var($validatedData['first_name'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		$validatedData['last_name'] = filter_var($validatedData['last_name'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if (isset($validatedData['email'])) {
			$validatedData['email'] = filter_var($validatedData['email'], FILTER_SANITIZE_EMAIL);
			$validatedData['email_verified_at'] = null;
		}

		$user->update($validatedData);

		if (isset($validatedData['email'])) {
			$user->sendEmailVerificationNotification();
		}

		if ($request->has('locations')) {
			$user->countries()->sync($request->locations);
		}

		\Session::flash('alert', 'Success:Your profile has been updated.');

		return redirect()->back();
	}

	public function updateProfilePicture(Request $request)
	{
		$request->validate([
			'profile' => 'required|mimes:jpeg,jpg,png|max:5120',
		]);
		$disk = \Storage::disk('public');
		$user = $request->user();
		$currentImageName = basename($user->profile_picture);
		$isDefaultImage = strpos($currentImageName, 'profile-') !== false;
		$imageName = $isDefaultImage || strlen($currentImageName) > 20 ? base64_encode(date('iYHs') . rand(1, 24)) . '.png' : $currentImageName;
		$imageName = 'profile/' . $imageName;
		$currentImageName = 'profile/' . $currentImageName;

		if (!$isDefaultImage && $disk->exists($currentImageName)) {
			$disk->delete($currentImageName);
		}

		$image = Image::make($request->file('profile'))
			->fit(452, 452, function ($constraint) {
				$constraint->aspectRatio();
				$constraint->upsize();
			});

		$success = $disk->put($imageName, (string) $image->encode('png', 95));
		$request->user()->update(['profile_picture' => '/storage/' . $imageName]);

		return response()->json([
			'success' => $success,
		]);
	}

	public function enable2fa(Request $request)
	{
		$request->user()->update([
			'2fa' => $request->key
		]);

		return response()->json(['success' => true, 'message' => 'Key has been added.'], 200);
	}

	public function disable2fa(Request $request)
	{
		$request->user()->update(['2fa' => null]);

		return redirect()->back()->with('alert', "Success:2FA has been disabled");
	}

	public function verify2fa(Request $request)
	{
		$message = "Success:2FA has been verified";

		if ($request->has('one_time_key')) {
			$verify = Google2FA::verifyGoogle2FA($request->one_time_key, $request->one_time_password);

			if (!$verify) {
				return redirect(URL()->previous())->with('alert', "Error:Your one time password didn't match;Please try again.");
			}

			$request->user()->update([
				'2fa' => $request->one_time_key
			]);

			Google2FA::login();

			$message = "Success:2FA has been enabled";
		}

		if (strpos(url()->previous(), '2fa/verify') !== false) {
			return redirect()->route('app.index')->with('alert', $message);
		}

		return redirect()->back()->with('alert', $message);
	}
}
